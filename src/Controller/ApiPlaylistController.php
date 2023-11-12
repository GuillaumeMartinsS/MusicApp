<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Models\JsonError;
use App\Repository\PlaylistRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiPlaylistController extends AbstractController
{
    /**
     * @Route("/api/playlists", name="api_playlist", methods={"GET"})
     */
    public function listPlaylist(PlaylistRepository $playlistRepository): Response
    {
        return $this->json(
            $playlistRepository->findAll(),
            200,
            [],
            ['groups'=> ['list_playlist']]
        );
    }

    /**
     * @Route("/api/playlists/{id}", name="api_playlist_id", methods={"GET"})
     */
    public function showPlaylist(Playlist $playlist = null)
    {
        if ($playlist === null){
            $error = new JsonError(Response::HTTP_NOT_FOUND, Playlist::class . ' non trouvé');
            return $this->json($error, $error->getError());
        }

        return $this->json(
            $playlist,
            Response::HTTP_OK,
            [],
            ['groups' => 'show_playlist']
        );
    }
}
