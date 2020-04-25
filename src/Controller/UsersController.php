<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UsersController extends AbstractController
{
    public function GetUsers()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        if (!$users){
            return new Response("Table is empty");
        }
        $arrayCollection = array();

        foreach($users as $user) {
            $arrayCollection[] = array(
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone()
            );
        }

        return new JsonResponse($arrayCollection);
    }

    public function GetUsero($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if (!$user){
            return new Response('User not found');
        }
        $userJSON = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone()
        ];
        return new JsonResponse($userJSON);
    }

    public function PostUser(Request $request):Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPhone($request->request->get('phone'));
        $user->setCreatedTime(new \DateTime('now'));
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('User has been successfully created with ID: '.$user->getId());
    }

    public function PutUser($id, Request $request):Response{
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if(!$user){
            $user = new User();
            $user->setName($request->request->get('name'));
            $user->setEmail($request->request->get('email'));
            $user->setPhone($request->request->get('phone'));
            $user->setCreatedTime(new \DateTime('now'));
            $entityManager->persist($user);
            $entityManager->flush();
            return new Response('User has been successfully created with ID: '.$user->getId());
        }else{
            $user->setName($request->request->get('name'));
            $user->setEmail($request->request->get('email'));
            $user->setPhone($request->request->get('phone'));
            $user->setUpdatedTime(new \DateTime('now'));
            $entityManager->persist($user);
            $entityManager->flush();
            return new Response('User has been successfully changed with ID: '.$user->getId());
        }
    }

    public function DeleteUser($id){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) return new Response('User not found');
        $entityManager->remove($user);
        $entityManager->flush();
        return new Response('User with ID '.$id.' has been successfully deleted');
    }
}