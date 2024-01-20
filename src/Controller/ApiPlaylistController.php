<?php

namespace App\Controller;

use DateTime;
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
use Symfony\Component\Validator\Constraints\Image;
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
    public function createPlaylist(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, UserRepository $userRepository, SongRepository $songRepository)
    {
        $newPlaylist = new Playlist();

        $newPlaylist->setName($request->request->get('name'));
        $newPlaylist->setDescription($request->request->get('description'));
        $newPlaylist->setAlbum($request->request->get('album'));
        $newPlaylist->setStatus(1);
        $newPlaylist->setNbLike(0);
        $newPlaylist->setCreatedAt(new DateTimeImmutable('now'));

        
        $upload = $request->files;
        $uploadFile = $upload->get('picture');
        $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();
            
        $errors = $validator->validate($uploadFile, new Image([]));
    
        // if errors are found, we send a Json error
        if (count($errors) > 0) {
    
            $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
            $myJsonError->setValidationErrors($errors);
                
            return $this->json($myJsonError, $myJsonError->getError());
            }
        
    
        // f no error is found, we move the file to the upload_directory setting on the services.yaml file
        $uploadFile->move(
            $this->getParameter('upload_directory'),
            $uploadedName
            );

        $newPlaylist->setPicture($uploadedName);

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

    /**
     * @Route("/api/playlists/edit/{id}", name="api_playlist_edit", methods={"POST"})
     */
    public function updatePlaylist(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, Playlist $playlist, UserRepository $userRepository, SongRepository $songRepository)
    {
        
        if ($request->request->get('name')!== null) {
            $playlist->setName($request->request->get('name'));
        }
        
        if ($request->request->get('description')!== null) {
            $playlist->setDescription($request->request->get('description'));
        }

        if ($request->request->get('album')!== null) {
            $playlist->setDescription($request->request->get('album'));
        }

        $playlist->setUpdatedAt(new DateTime('now'));

        $uploadFile = $request->files->get('picture');

        if ($uploadFile !== null) {
        {
            $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();
                
            $errors = $validator->validate($uploadFile, new Image([]));
        
            // if errors are found, we send a Json error
            if (count($errors) > 0) {
        
                $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
                $myJsonError->setValidationErrors($errors);
                    
                return $this->json($myJsonError, $myJsonError->getError());
                }
            
        
            // f no error is found, we move the file to the upload_directory setting on the services.yaml file
            $uploadFile->move(
                $this->getParameter('upload_directory'),
                $uploadedName
                );
    
            $playlist->setPicture($uploadedName);
            }
        }
        

        if ($request->request->get('songs')!== null) 
        {
            $songs = json_decode($request->request->get('songs'));

            foreach ($songs as $key => $song)
            {
                $playlist->addSong($songRepository->find($song));
            }
        }

        $entityManager->persist($playlist);
        $entityManager->flush();

        return $this->json(
            $playlist,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_playlist']]
        );
    


    }
}
