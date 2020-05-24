<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Chat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat/{id}", name="chat")
     */
    public function index(Booking $booking)
    {
        return $this->render('chat/index.html.twig', [
            'booking' => $booking
        ]);
    }

    /**
     * Permet d'inserer le nouveau message arrivant de la requete ajax
     * 
     * @Route("/chat/insert", name="chat_insert")
     * @return void
     */
    public function insertMessage(Request $request, BookingRepository $bookingRepository, UserRepository $userRepository, EntityManagerInterface $manager)
    {
        $postData = json_decode($request->getContent());
        $bookingId = $postData->bookingId;
        $userId = $postData->userId;
        $message = $postData->message;
        $booking = $bookingRepository->findOneById($bookingId);
        $author = $userRepository->findOneById($userId);
        $chat = new Chat;
        $chat->setBooking($booking)
            ->setAuthor($author)
            ->setCreatedAt(new \DateTime())
            ->setMessage($message);
        $manager->persist($chat);
        $manager->flush();
    }

    /**
     * Permet d'envoyer les nouveaux message à ajax
     *
     * @Route("/chat/select", name="chat_select")
     * @return json
     */
    public function selectMessages(Request $request, BookingRepository $bookingRepository, UserRepository $userRepository)
    {
        $postData = json_decode($request->getContent());
        $bookingId = $postData->bookingId;
        $userId = $postData->userId;
        $message = $postData->message;
        $booking = $bookingRepository->findOneById($bookingId);
        $author = $userRepository->findOneById($userId);
    }
}
