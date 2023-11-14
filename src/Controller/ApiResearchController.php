<?php

namespace App\Controller;

use App\Repository\SongRepository;
use App\Repository\UserRepository;
use App\Repository\GenreRepository;
use App\Repository\PlaylistRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiResearchController extends AbstractController
{

    /**
     * @Route("/api/research", name="api_research", methods="POST")
     */
    public function ListResearch(Request $request, SongRepository $songRepository, UserRepository $userRepository, PlaylistRepository $playlistRepository,GenreRepository $genreRepository): Response
    {
        // Doing it with POST method
        $filter = $request->getcontent();
        $filterDecoded = json_decode($filter);
        // dd ($filterDecoded);
        $filterResult = $filterDecoded->research;

        // Doing it with GET method
        // $filterResult = $request->query->get('filter');
        
   
        $songResult = $songRepository->findSongByTitle($filterResult);
        // dump($songResult);
        $userResult = $userRepository->findUserByName($filterResult);
        // dump($userResult);
        $playlistResult = $playlistRepository->findPlaylistByName($filterResult);
        // dump($playlistResult);
        $genreResult = $genreRepository->findGenreByName($filterResult);
        // dd($genreResult);

        return $this->json(
            ['song' => $songResult,
            'user' => $userResult,
            'playlist' => $playlistResult,
            'genre' => $genreResult],
            200,
            [],
            ['groups'=> ['list_song'],['list_user'],['list_playlist'],['list_genre']]
        );
    }
}
