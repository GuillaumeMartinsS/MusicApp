<?php

namespace App\Controller\BackOffice;

use DateTime;
use App\Entity\Song;
use App\Form\SongType;
use DateTimeImmutable;
use App\Repository\SongRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/song")
 */
class BackSongController extends AbstractController
{
    /**
     * @Route("/", name="app_back_song_index", methods={"GET"})
     */
    public function index(SongRepository $songRepository): Response
    {
        return $this->render('back_song/index.html.twig', [
            'songs' => $songRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_song_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SongRepository $songRepository): Response
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $song->setCreatedAt(new DateTimeImmutable('now'));
            $uploadPicture = $form->get('picture')->getData();

                if ($uploadPicture) {
                    $uploadedNamePicture = md5(uniqid()) . '.' . $uploadPicture->guessExtension();

                    $uploadPicture->move(
                        $this->getParameter('upload_directory'),
                        $uploadedNamePicture
                    );
                
                    $song->setPicture($uploadedNamePicture);
                }

            $uploadSong = $form->get('file')->getData();

                if ($uploadSong) {
                    $uploadedNameSong = md5(uniqid()) . '.' . $uploadSong->guessExtension();

                    $uploadSong->move(
                        $this->getParameter('upload_directory'),
                        $uploadedNameSong
                    );
                
                    $song->setFile($uploadedNameSong);
                }
            $songRepository->add($song, true);

            return $this->redirectToRoute('app_back_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_song/new.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_song_show", methods={"GET"})
     */
    public function show(Song $song): Response
    {
        return $this->render('back_song/show.html.twig', [
            'song' => $song,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_song_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Song $song, SongRepository $songRepository): Response
    {
        $songFirstPicture = $song->getPicture();
        $songFirstFile = $song->getFile();

        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $song->setUpdatedAt(new DateTime ('now'));

            $uploadPicture = $form->get('picture')->getData();

                if ($uploadPicture != null) {
                    $uploadedNamePicture = md5(uniqid()) . '.' . $uploadPicture->guessExtension();

                    $uploadPicture->move(
                        $this->getParameter('upload_directory'),
                        $uploadedNamePicture
                    );
                
                    $song->setPicture($uploadedNamePicture);
                }else {
                    $song->setPicture($songFirstPicture);
                }

            $uploadSong = $form->get('file')->getData();

                if ($uploadSong != null) {
                    $uploadedNameSong = md5(uniqid()) . '.' . $uploadSong->guessExtension();

                    $uploadSong->move(
                        $this->getParameter('upload_directory'),
                        $uploadedNameSong
                    );
                
                    $song->setFile($uploadedNameSong);
                }else {
                    $song->setFile($songFirstFile);
                }
            $songRepository->add($song, true);

            return $this->redirectToRoute('app_back_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_song/edit.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_song_delete", methods={"POST"})
     */
    public function delete(Request $request, Song $song, SongRepository $songRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))) {
            $songRepository->remove($song, true);
        }

        return $this->redirectToRoute('app_back_song_index', [], Response::HTTP_SEE_OTHER);
    }
}
