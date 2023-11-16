<?php

namespace App\Controller\BackOffice;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/playlist")
 */
class BackPlaylistController extends AbstractController
{
    /**
     * @Route("/", name="app_back_playlist_index", methods={"GET"})
     */
    public function index(PlaylistRepository $playlistRepository): Response
    {
        return $this->render('back_playlist/index.html.twig', [
            'playlists' => $playlistRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_playlist_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PlaylistRepository $playlistRepository): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playlistRepository->add($playlist, true);

            return $this->redirectToRoute('app_back_playlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_playlist/new.html.twig', [
            'playlist' => $playlist,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_playlist_show", methods={"GET"})
     */
    public function show(Playlist $playlist): Response
    {
        return $this->render('back_playlist/show.html.twig', [
            'playlist' => $playlist,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_playlist_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Playlist $playlist, PlaylistRepository $playlistRepository): Response
    {
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playlistRepository->add($playlist, true);

            return $this->redirectToRoute('app_back_playlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_playlist/edit.html.twig', [
            'playlist' => $playlist,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_playlist_delete", methods={"POST"})
     */
    public function delete(Request $request, Playlist $playlist, PlaylistRepository $playlistRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->request->get('_token'))) {
            $playlistRepository->remove($playlist, true);
        }

        return $this->redirectToRoute('app_back_playlist_index', [], Response::HTTP_SEE_OTHER);
    }
}
