<?php

namespace App\Controller;

use App\Entity\Song;
use App\Models\JsonError;
use App\Repository\SongRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiSongController extends AbstractController
{
    /**
     * @Route("/api/songs", name="api_song", methods={"GET"})
     */
    public function listSong(SongRepository $songRepository) : Response
    {
        return $this->json(
            $songRepository->findAll(),
            200,
            [],
            ['groups'=> ['list_song']]
        );
    }

    /**
     * @Route("/api/songs/[id]", name="api_song_id", methods={"GET"})
     */
    public function showSong(Song $song)
    {
        if ($music === null){
            $error = new JsonError(Response::HTTP_NOT_FOUND, Song::class . ' non trouvé');
            return $this->json($error, $error->getError());
        }

        return $this->json(
            $song,
            Response::HTTP_OK,
            [],
            ['groups' => 'show_song']
        );
    }
}
