<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_user", methods={"GET"})
     */
    public function listUser(UserRepository $userRepository): Response
    {
        // dd($userRepository);
        return $this->json(
            $userRepository->findAll(),
            200,
            [],
            ['groups'=> ['list_user']]
        );
    }

    /**
     * @Route("/api/users/{id}", name="api_user_id", methods={"GET"})
     */
    public function showUser(User $user = null)
    {
        // dump($user);
        if ($user === null){
            $error = new JsonError(Response::HTTP_NOT_FOUND, User::class . ' non trouvé');
            return $this->json($error, $error->getError());
        }

        return $this->json(
            $user,
            Response::HTTP_OK,
            [],
            ['groups' => 'show_user']
        );
    }
}
