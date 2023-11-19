<?php

namespace App\Controller\BackOffice;

use DateTime;
use App\Entity\Genre;
use DateTimeImmutable;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/genre")
 */
class BackGenreController extends AbstractController
{
    /**
     * @Route("/", name="back_genre_index", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('back_genre/index.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_genre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GenreRepository $genreRepository): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genre->setCreatedAt(new DateTimeImmutable('now'));
            //! To Do with an event :
            $genre->setSlug('slugachanger');
            $uploadFile = $form->get('picture')->getData();

                if ($uploadFile) {
                    $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();

                    $uploadFile->move(
                        $this->getParameter('upload_directory'),
                        $uploadedName
                    );
                
                    $genre->setPicture($uploadedName);
                }
            $genreRepository->add($genre, true);

            return $this->redirectToRoute('back_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_genre_show", methods={"GET"})
     */
    public function show(Genre $genre): Response
    {
        return $this->render('back_genre/show.html.twig', [
            'genre' => $genre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_genre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Genre $genre, GenreRepository $genreRepository): Response
    {
        $genreFirstPicture = $genre->getPicture();

        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genre->setUpdatedAt(new DateTime ('now'));
            //! To Change :
            $uploadFile = $form->get('picture')->getData();

                if ($uploadFile != null) {
                    $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();

                    $uploadFile->move(
                        $this->getParameter('upload_directory'),
                        $uploadedName
                    );
                
                    $genre->setPicture($uploadedName);
                } else {
                    $genre->setPicture($genreFirstPicture);
                }
            $genreRepository->add($genre, true);

            return $this->redirectToRoute('back_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_genre_delete", methods={"POST"})
     */
    public function delete(Request $request, Genre $genre, GenreRepository $genreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $genreRepository->remove($genre, true);
        }

        return $this->redirectToRoute('back_genre_index', [], Response::HTTP_SEE_OTHER);
    }
}
