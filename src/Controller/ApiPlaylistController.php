<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Playlist;
use App\Models\JsonError;
use App\Repository\SongRepository;
use App\Repository\UserRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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

    /**
     * @Route("/api/playlists", name="api_playlist_create", methods={"POST"})
     */
    public function new(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, UserRepository $userRepository, SongRepository $songRepository): Response
    {
        $newPlaylist = new Playlist();

        $newPlaylist->setName($request->request->get('name'));
        $newPlaylist->setDescription($request->request->get('description'));
        $newPlaylist->setAlbum($request->request->get('album'));
        $newPlaylist->setStatus(1);
        $newPlaylist->setCreatedAt(new DateTimeImmutable('now'));

        
            // $uploadFile = $request->files;
            // $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();
            
            // $errors = $validator->validate($uploadFile, new Image([]));
    
            // // if errors are found, we send a Json error
            // if (count($errors) > 0) {
    
            //     $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
            //     $myJsonError->setValidationErrors($errors);
                
            //     return $this->json($myJsonError, $myJsonError->getError());
            // }
        
    
        // if no error is found, we move the file to the upload_directory setting on the services.yaml file
        // $uploadFile->move(
        //     $this->getParameter('upload_directory'),
        //     $uploadedName
        //     );

        // $newPlaylist->setPicture($uploadedName);

        $newPlaylist->setUser($this->getUser());
        // $user = $request->request->get('user');

        $songs = json_decode($request->request->get('songs'));

        foreach ($songs as $key => $song)
        {
            $newPlaylist->addSong($songRepository->find($song));
        }

        $entityManager->persist($newPlaylist);
        $entityManager->flush();

        return $this->json(
            $newPlaylist,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_playlist']]
        );
    }


}
