<?php


namespace App\Controller;

use App\Entity\File;
use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManagerInterface;

class MessagesController extends AbstractController
{
    public function GetMessages($userId)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        if(!$user) return new Response('User with ID '.$userId.' not found');
        $messages = $user->getMessages();
        if(count($messages)===0) return new Response('User with ID '.$userId.' does not have any messages');
        $arrayCollection = array();

        foreach($messages as $message) {
            $arrayCollection[] = array(
                'id' => $message->getId(),
                'sender' => $userId,
                'topic' => $message->getTopic(),
                'messageText' => $message->getMessageText(),
                'parentMessageId' => $message->getParentMessage(),
                'receiver' => $message->getReceiver(),
                'createdTime' => $message->getCreatedTime(),
            );
        }

        return new JsonResponse($arrayCollection);
    }

    public function GetMessage($id)
    {
        $message = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($id);
        if (!$message){
            return new Response('Message not found');
        }
        $fileJSON = [
            'id' => $message->getId(),
            'sender' => $message->getSender(),
            'topic' => $message->getTopic(),
            'messageText' => $message->getMessageText(),
            'parentMessageId' => $message->getParentMessage(),
            'receiver' => $message->getReceiver(),
            'createdTime' => $message->getCreatedTime(),
        ];
        return new JsonResponse($fileJSON);
    }

    public function PostMessage(Request $request):Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $message = new Message();
        $senderId = $request->request->get('senderId');
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($senderId);
        if(!$user){
            return new Response('User not found');
        }
        if ($request->request->get('parentId')){
            $parentId = $request->request->get('parentId');
            $parentMessage = $this->getDoctrine()
                ->getRepository(Message::class)
                ->find($parentId);
            if (!$parentMessage) return new Response('Parent message not found');
            $message->setParentMessage($parentMessage);
        }
        $message->setSender($user);
        $message->setTopic($request->request->get('topic'));
        $message->setMessageText($request->request->get('messageText'));
        $message->setCreatedTime(new \DateTime('now'));
        $message->setReceiver($request->request->get('receiver'));
        $entityManager->persist($message);
        $entityManager->flush();
        return new Response('Message with ID '.$message->getId().' has been successfully created');
    }

    public function PutMessage($id, Request $request):Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $message = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($id);
        if (!$message) {
            $message = new Message();
            $senderId = $request->request->get('senderId');
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($senderId);
            if (!$user) {
                return new Response('User not found');
            }
            if ($request->request->get('parentId')) {
                $parentId = $request->request->get('parentId');
                $parentMessage = $this->getDoctrine()
                    ->getRepository(Message::class)
                    ->find($parentId);
                if (!$parentMessage) return new Response('Parent message not found');
                $message->setParentMessage($parentMessage);
            }
            $message->setSender($user);
            $message->setTopic($request->request->get('topic'));
            $message->setMessageText($request->request->get('messageText'));
            $message->setCreatedTime(new \DateTime('now'));
            $message->setReceiver($request->request->get('receiver'));
            $entityManager->persist($message);
            $entityManager->flush();
            return new Response('Message with ID ' . $message->getId() . ' has been successfully created');
        }
        else{
            $senderId = $request->request->get('senderId');
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($senderId);
            if (!$user) {
                return new Response('User not found');
            }
            if ($request->request->get('parentId')) {
                $parentId = $request->request->get('parentId');
                $parentMessage = $this->getDoctrine()
                    ->getRepository(Message::class)
                    ->find($parentId);
                if (!$parentMessage) return new Response('Parent message not found');
                $message->setParentMessage($parentMessage);
            }
            $message->setSender($user);
            $message->setTopic($request->request->get('topic'));
            $message->setMessageText($request->request->get('messageText'));
            $message->setCreatedTime(new \DateTime('now'));
            $message->setReceiver($request->request->get('receiver'));
            $entityManager->persist($message);
            $entityManager->flush();
            return new Response('Message with ID ' . $message->getId() . ' has been successfully changed');
        }
    }

    public function DeleteMessage($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $message = $entityManager->getRepository(Message::class)->find($id);
        if (!$message) return new Response('Message not found');
        $entityManager->remove($message);
        $entityManager->flush();
        return new Response('Message with ID '.$id.' has been successfully deleted');
    }
}