<?php

namespace App\Controller;

use App\Entity\Ad;
use Twig\Environment;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use App\Entity\CommentClient;
use App\Form\CommentClientType;
use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{id}/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $manager, MailerInterface $mailer)
    {
        $booking = new Booking;
        $booking->setAd($ad);
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $booking->setBooker($user)
                ->setAd($ad)
                ->setVuNotifClient(false)
                ->setVuNotifProp(false);

            if (!$booking->isBookable()) {
                $this->addFlash(
                    'warning',
                    "Les dates que vous avez choisies ne peuvent être réservées : elles sont déja prises."
                );
            } else {
                //$notification->notify($booking);
                $manager->persist($booking);
                $manager->flush();
                $subject = "Demande de confirmation de la reservation numero " . $booking->getId();
                $email = (new TemplatedEmail())
                    ->from($user->getEmail())
                    ->to($ad->getAuthor()->getEmail())
                    ->subject($subject)
                    ->htmlTemplate('emails/confirmation.html.twig')
                    ->context([
                        'booking' => $booking,
                        'to' => $ad->getAuthor(),
                        'from' => $user,
                    ]);
                $mailer->send($email);



                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withAlert' => true
                ]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de communiquer les notifications d'un utilisateur a ajax
     *
     * @Route("/booking/notif", name="booking_notif")
     * @return json
     */
    function showNotif(Request $request, BookingRepository $bookingRepository, UserRepository $userRepository)
    {
        $postData = json_decode($request->getContent());
        $userId = $postData->userId;
        $user = $userRepository->findOneById($userId);
        $bookings = $bookingRepository->getDemandes($user);
        foreach ($bookings as $booking) {
            $bookingArray[] = [
                'id' => $booking->getId(),
                'booker' => $booking->getBooker()->getFullName()
            ];
        }
        return $this->json($bookingArray, 200);
    }

    /**
     * Permet de communiquer les reservations términées et prêtes pour commenter au booker
     *
     * @Route("/booking/notifBooker", name="booking_notifBooker")
     * @return json
     */
    public function showNotifBooker(Request $request, BookingRepository $bookingRepository, UserRepository $userRepository)
    {
        $postData = json_decode($request->getContent());
        $userId = $postData->userId;
        $user = $userRepository->findOneById($userId);
        $bookings = $bookingRepository->getNotifBooker($user);
        foreach ($bookings as $booking) {
            $bookingArray[] = [
                'id' => $booking->getId(),
                'title' => $booking->getAd()->getTitle()
            ];
        }
        return $this->json($bookingArray, 200);
    }

    /**
     * Permet de communiquer les reservations términées et prêtes pour commenter a l'auteur
     *
     * @Route("/booking/notifAuthor", name="booking_notifAuthor")
     * @return json
     */
    public function showNotifAuthor(Request $request, BookingRepository $bookingRepository, UserRepository $userRepository)
    {
        $postData = json_decode($request->getContent());
        $userId = $postData->userId;
        $user = $userRepository->findOneById($userId);
        $bookings = $bookingRepository->getNotifAuthor($user);
        foreach ($bookings as $booking) {
            $bookingArray[] = [
                'id' => $booking->getId(),
                'booker' => $booking->getBooker()->getFullName()
            ];
        }
        return $this->json($bookingArray, 200);
    }

    /**
     * Permet d'afficher la page d'une reservation
     *
     * @Route("/booking/{id}", name="booking_show")
     * 
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        if ($this->getUser() == $booking->getBooker()) {
            $comment = new Comment;

            $form = $this->createForm(CommentType::class, $comment);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setAd($booking->getAd())
                    ->setAuthor($this->getUser());

                $manager->persist($comment);
                $booking->setVuNotifClient(true);
                $manager->persist($booking);
                $manager->flush();

                $this->addFlash(
                    "success",
                    "Votre commentaire a bien été pris en compte !"
                );
            }

            return $this->render('booking/show.html.twig', [
                'booking' => $booking,
                'form' => $form->createView()
            ]);
        } else {

            return $this->redirectToRoute("account_bookings");
        }
    }


    /**
     * Permet de confirmer une demande de reservation
     * 
     * @Route("/demande/{id}/confirm", name="demande_confirm")
     * 
     *
     * @return Response
     */
    function confirm(Booking $booking, EntityManagerInterface $manager)
    {
        $booking->setConfirm(1);
        $manager->persist($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La rervation num: <strong>{$booking->getId()}</strong> a bien été confirmée"
        );

        return $this->redirectToRoute("account_demande");
    }

    /**
     * permet d'afficher une seule demande de reservation
     *
     * @Route("/demande/{id}", name="demande_show")
     * 
     * @return Response
     */
    function showDemande(Booking $booking, Request $request, EntityManagerInterface $manager)

    {
        if ($this->getUser() == $booking->getAd()->getAuthor()) {
            $commentClient = new CommentClient;

            $form = $this->createForm(CommentClientType::class, $commentClient);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $commentClient->setBooking($booking)
                    ->setAuthor($this->getUser())

                    ->setCreatedAt(new \DateTime());

                $manager->persist($commentClient);
                $booking->setVuNotifProp(true);
                $manager->persist($booking);
                $manager->flush();

                $this->addFlash(
                    "success",
                    "Votre commentaire a bien été pris en compte !"
                );

                return $this->redirectToRoute("demande_show", array('id' => $booking->getiD()));
            }

            return $this->render('booking/demande.html.twig', [
                "booking" => $booking,
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute("account_demande");
        }
    }

    /**
     * Permet de supprimer une reservation non confirmée
     * 
     * @Route("/demande/{id}/delete", name="demande_delete")
     *
     * @return Response
     */
    function delete(Booking $booking, EntityManagerInterface $manager)
    {
        //$manager->remove($booking);
        $booking->delete();
        $manager->persist($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La demande <strong>{$booking->getId()}</strong> a bien été supprimée"
        );

        return $this->redirectToRoute("account_demande");
    }
}
