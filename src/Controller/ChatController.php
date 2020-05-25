<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Chat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BookingRepository;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class ChatController extends AbstractController
{
    /**
     * Permet d'inserer le nouveau message arrivant de la requete ajax
     * 
     * @Route("/chat/insert", name="chat_insert")
     */
    public function insertMessage(Request $request, BookingRepository $bookingRepository, UserRepository $userRepository, EntityManagerInterface $manager)
    {
        $postData = json_decode($request->getContent());
        $bookingId = $postData->bookingId;
        $userId = $postData->userId;
        $message = $postData->message;
        $booking = $bookingRepository->findOneById($bookingId);
        $author = $userRepository->findOneById($userId);
        if ($booking->getBooker() === $author) {
            $sendTo = $booking->getAd()->getAuthor();
        } else {
            $sendTo = $booking->getBooker();
        }
        $chat = new Chat;
        $chat->setBooking($booking)
            ->setAuthor($author)
            ->setCreatedAt(new \DateTime())
            ->setMessage($message)
            ->setSeen(false)
            ->setSendTo($sendTo);
        $manager->persist($chat);
        $manager->flush();
        $messageArray[] = [
            'id' => $chat->getId()
        ];
        return $this->json($messageArray, 200);
    }

    /**
     * Permet d'envoyer les messages non vu à ajax
     *
     * @Route("/chat/notif", name="chat_notif")
     * @return json
     */
    public function showNotifMessages(Request $request, BookingRepository $bookingRepository, UserRepository $userRepository, ChatRepository $chatRepository)
    {
        $postData = json_decode($request->getContent());
        $userId = $postData->userId;
        $author = $userRepository->findOneById($userId);
        $chats = $chatRepository->getNotif($author);
        $bookingObjects = [];
        foreach ($chats as $chat) {
            if (in_array($chat->getBooking(), $bookingObjects)) {
                continue;
            } else {
                $bookingObjects[] = $chat->getBooking();
            }
        }
        foreach ($bookingObjects as $booking) {
            $bookingArray[] = [
                'id' => $booking->getId()
            ];
        }
        return $this->json($bookingArray, 200);
    }

    /**
     * Permet de marquer les messages non lus comme étant lu à travers ajax
     *
     * @Route("/chat/setSeen", name="chat_set_seen")
     * @return void
     */
    public function setSeen(Request $request, BookingRepository $bookingRepository, EntityManagerInterface $manager)
    {
        $postData = json_decode($request->getContent());
        $bookingId = $postData->bookingId;
        $booking = $bookingRepository->findOneById($bookingId);
        $chats = $booking->getChats();
        foreach ($chats as $chat) {
            if (!$chat->getSeen()) {
                $chat->setSeen(true);
                $manager->persist($chat);
                $manager->flush();
            }
        }
    }


    /**
     * Permet d'envoyer les messages à ajax
     *
     * @Route("/chat/selectMessages", name="chat_select_messages")
     * @return json
     */
    public function selectMessages(Request $request, BookingRepository $bookingRepository, ChatRepository $chatRepository)
    {
        $postData = json_decode($request->getContent());
        $bookingId = $postData->bookingId;
        $userId = $postData->userId;
        $booking = $bookingRepository->findOneById($bookingId);
        $booker = $booking->getBooker();
        $author = $booking->getAd()->getAuthor();
        $messages = $chatRepository->getMessages($booking, $booker, $author);
        if ($userId == $booker->getId()) {
            $chatterPicture = $author->getPicture();
            $chatterFullName = $author->getFullName();
        } else {
            $chatterPicture = $booker->getPicture();
            $chatterFullName = $booker->getFullName();
        }
        foreach ($messages as $message) {
            $messageArray[] = [
                'id' => $message->getId(),
                'authorId' => $message->getAuthor()->getId(),
                'authorFullName' => $message->getAuthor()->getFullName(),
                'authorPicture' => $message->getAuthor()->getPicture(),
                'message' => $message->getMessage(),
                'chatterPicture' => $chatterPicture,
                'chatterFullName' => $chatterFullName
            ];
        }
        return $this->json($messageArray, 200);
    }

    /**
     * @Route("/chat/seen/{id}", name="chat_seen")
     */
    public function index_seen(Booking $booking, EntityManagerInterface $manager)
    {
        $chats = $booking->getChats();
        foreach ($chats as $chat) {
            if (!$chat->getSeen()) {
                $chat->setSeen(true);
                $manager->persist($chat);
                $manager->flush();
            }
        }
        return $this->render('chat/index.html.twig', [
            'booking' => $booking
        ]);
    }


    /**
     * @Route("/chat/{id}", name="chat")
     */
    public function index(Booking $booking)
    {
        return $this->render('chat/index.html.twig', [
            'booking' => $booking
        ]);
    }
}
