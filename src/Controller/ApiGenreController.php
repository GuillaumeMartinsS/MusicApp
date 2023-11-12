<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Entity\Genre;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genre", methods={"GET"})
     */
    public function listGenre(GenreRepository $genreRepository): Response
    {
        return $this->json(
            $genreRepository->findAll(),
            200,
            [],
            ['groups'=> ['list_genre']]
        );
    }

    /**
     * @Route("/api/genres/{id}", name="api_genre_id", methods={"GET"})
     */
    public function showGenre(Genre $genre = null)
    {
        if ($genre === null){
            //TO DO
        }

        return $this->json(
            $genre,
            Response::HTTP_OK,
            [],
            ['groups' => 'show_genre']
        );
    }
}
