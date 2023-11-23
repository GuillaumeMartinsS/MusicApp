<?php

namespace App\Controller;

use DateTime;
use App\Entity\Song;
use DateTimeImmutable;
use App\Models\JsonError;
use App\Repository\SongRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

    /**
     * Route to upload a song
     * @Route("/api/songs", name="api_song_create", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function createSong(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, UserRepository $userRepository)
    {        
        $songEntity = new Song();
        $songEntity->setTitle($request->request->get('title'));
        $songEntity->setDescription($request->request->get('description'));

        $dateObject = new DateTime();
        // We change the Date to get it on this format : d/m/y
        $changedDate = $dateObject->createFromFormat('d/m/Y',$request->request->get('releasedate'));
        $songEntity->setReleaseDate($changedDate);

        $songEntity->setStatus(1);
        $songEntity->setCreatedAt(new DateTimeImmutable('now'));

        // To get the user uploading the song
        $trueUser = $userRepository->find($request->request->get('user'));

        $songEntity->addUser($trueUser);

        // files are found this way :
        $upload = $request->files;

        // As there is 2 medias (an audio file and an image file), we make a loop :
        foreach ($upload as $key => $uploadFile)
        {
            // We change the file name and make it finish by the extension
            $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();

            // If this is the picture file, we validate it with validator constraints
            if($key === 'picture'){
                $errors = $validator->validate($uploadFile, new Image([]));

                // if errors are found, we send a Json error
                if (count($errors) > 0) {

                    $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
                    $myJsonError->setValidationErrors($errors);
            
                    return $this->json($myJsonError, $myJsonError->getError());
                }

            // If this is the audio file, we validate it with validator constraints    
            } else {
                // dd($validator->validate($uploadFile, new File(['mimeTypes' => 'audio/*'])));
                $errors = $validator->validate($uploadFile, new File(['mimeTypes' => 'audio/*']));

                // if errors are found, we send a Json error
                if (count($errors) > 0) {

                    $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
                    $myJsonError->setValidationErrors($errors);
            
                    return $this->json($myJsonError, $myJsonError->getError());
                }
            }
            
            // if no error is found, we move the file to the upload_directory setting on the services.yaml file
            $uploadFile->move(
                $this->getParameter('upload_directory'),
                $uploadedName
            );

            // if the file is a picture, its name goes as a value for the picture property
            if($key === 'picture'){
                $songEntity->setPicture($uploadedName);

            // if the file is an audio file, its name goes as a value for the file property
            } else {
                $songEntity->setFile($uploadedName);
            }

        }
        
        $entityManager->persist($songEntity);
        $entityManager->flush();

        return $this->json(
            $songEntity,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_song']]
        );
    }

    /**
     * @Route("/api/songs/edit/{id}", name="api_song_edit", methods={"POST"})
     */
    public function updateSong(EntityManagerInterface $entityManager, Song $song, Request $request,ValidatorInterface $validator)
    {        
        // print_r($_FILES);
        // dd(ini_get('upload_max_filesize'));
        // dd($request);
        if ($request->request->get('title')!== null) {
            $song->setTitle($request->request->get('title'));
        }
        if ($request->request->get('description')!== null) {
            $song->setDescription($request->request->get('description'));
        }
        if ($request->request->get('releasedate')!== null) {
        $dateObject = new DateTime();
        $changedDate = $dateObject->createFromFormat('d/m/Y',$request->request->get('releasedate'));
        $song->setReleaseDate($changedDate);
        }
        
        $song->setStatus(1);
        $song->setUpdatedAt(new DateTime('now'));

        // we check if there is any file given by the request
        if ($request->files !== null) 
        {
            $upload = $request->files;
            // dd ($upload);
            foreach ($upload as $key => $uploadFile) 
            {
                // We change the file name and make it finish by the extension
                $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();

                // If this is the picture file, we validate it with validator constraints
                if($key === 'picture') 
                {
                    $errors = $validator->validate($uploadFile, new Image([]));

                    // if errors are found, we send a Json error
                    if (count($errors) > 0) {

                        $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
                        $myJsonError->setValidationErrors($errors);

                        return $this->json($myJsonError, $myJsonError->getError());
                    }
                }

                // If this is the audio file, we validate it with validator constraints
                else 
                {
                    // dd($validator->validate($uploadFile, new File(['mimeTypes' => 'audio/*'])));
                    $errors = $validator->validate($uploadFile, new File(['mimeTypes' => 'audio/*']));

                    // if errors are found, we send a Json error
                    if (count($errors) > 0) {

                        $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
                        $myJsonError->setValidationErrors($errors);

                        return $this->json($myJsonError, $myJsonError->getError());
                    }
                }

                // if no error is found, we move the file to the upload_directory setting on the services.yaml file
                $uploadFile->move(
                    $this->getParameter('upload_directory'),
                    $uploadedName);

                // if the file is a picture, it goes as a value for the picture property
                if($key === 'picture') 
                {
                    $song->setPicture($uploadedName);
                } 

                // if the file is an audio file, it goes as a value for the file property
                else {
                    $song->setFile($uploadedName);
                }
            }
        }

    
        $entityManager->persist($song);
        $entityManager->flush();

        return $this->json(
            $song,
            201,
            [],
            ['groups' => ['show_song']]
        );
    }
}
