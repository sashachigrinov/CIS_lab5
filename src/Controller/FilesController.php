<?php


namespace App\Controller;

use App\Entity\Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\File;
use App\Entity\User;

class FilesController extends AbstractController
{
    public function GetFiles($userId)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        if(!$user) return new Response('User with ID '.$userId.' not found');
        $files = $user->getFiles();
        if(count($files)===0) return new Response('User with ID '.$userId.' does not have any files');
        $arrayCollection = array();

        foreach($files as $file) {
            $arrayCollection[] = array(
                'id' => $file->getId(),
                'userId' => $userId,
                'name' => $file->getName(),
                'description' => $file->getDescription(),
                'extension' => $file->getExtension(),
                'mime' => $file->getMime(),
            );
        }

        return new JsonResponse($arrayCollection);
    }

    public function GetFile($id)
    {
        $file = $this->getDoctrine()
            ->getRepository(File::class)
            ->find($id);
        if (!$file){
            return new Response('File not found');
        }
        $fileJSON = [
            'id' => $file->getId(),
            'name' => $file->getName(),
            'description' => $file->getDescription(),
            'extension' => $file->getExtension(),
            'mime' => $file->getMime(),
        ];
        return new JsonResponse($fileJSON);
    }

    public function PostFile(Request $request):Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $file = new File();
        $userId = $request->request->get('userId');
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        if(!$user){
            return new Response('User not found');
        }
        if ($request->request->get('messageId')){
            $messageId = $request->request->get('messageId');
            $message = $this->getDoctrine()
                ->getRepository(Message::class)
                ->find($messageId);
            if (!$message) return new Response('Message not found');
            $file->setMessage($message);
        }
        $file->setUser($user);
        $file->setName($request->request->get('name'));
        $file->setDescription($request->request->get('description'));
        $file->setExtension($request->request->get('extension'));
        $file->setMime($request->request->get('mime'));
        $file->setSize(random_int(255,89454));
        $entityManager->persist($file);
        $entityManager->flush();
        return new Response('File with ID '.$file->getId().' has been successfully created');
    }

    public function PutFile($id, Request $request):Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $file = $this->getDoctrine()
            ->getRepository(File::class)
            ->find($id);
        if (!$file){
            $file = new File();
            $userId = $request->request->get('userId');
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($userId);
            if(!$user){
                return new Response('User not found');
            }
            if ($request->request->get('messageId')){
                $messageId = $request->request->get('messageId');
                $message = $this->getDoctrine()
                    ->getRepository(Message::class)
                    ->find($messageId);
                if (!$message) return new Response('Message not found');
                $file->setMessage($message);
            }
            $file->setUser($user);
            $file->setName($request->request->get('name'));
            $file->setDescription($request->request->get('description'));
            $file->setExtension($request->request->get('extension'));
            $file->setMime($request->request->get('mime'));
            $file->setSize(random_int(255,89454));
            $entityManager->persist($file);
            $entityManager->flush();
            return new Response('File with ID '.$file->getId().' has been successfully created');
        }
        else{
            $userId = $request->request->get('userId');
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($userId);
            if(!$user){
                return new Response('User not found');
            }
            if ($request->request->get('messageId')){
                $messageId = $request->request->get('messageId');
                $message = $this->getDoctrine()
                    ->getRepository(Message::class)
                    ->find($messageId);
                if (!$message) return new Response('Message not found');
                $file->setMessage($message);
            }
            $file->setUser($user);
            $file->setName($request->request->get('name'));
            $file->setDescription($request->request->get('description'));
            $file->setExtension($request->request->get('extension'));
            $file->setMime($request->request->get('mime'));
            $file->setSize(random_int(255,89454));
            $entityManager->persist($file);
            $entityManager->flush();
            return new Response('File with ID '.$file->getId().' has been successfully changed');
        }
    }

    public function DeleteFile($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $file = $entityManager->getRepository(File::class)->find($id);
        if (!$file) return new Response('File not found');
        $entityManager->remove($file);
        $entityManager->flush();
        return new Response('File with ID '.$id.' has been successfully deleted');
    }
}