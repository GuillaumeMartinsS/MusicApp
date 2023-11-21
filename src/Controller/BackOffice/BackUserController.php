<?php

namespace App\Controller\BackOffice;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/back/user")
 */
class BackUserController extends AbstractController
{
    /**
     * @Route("/", name="back_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back_user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserPasswordHasherInterface $encoder, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new DateTimeImmutable('now'));

            // now done with an event
            // $user->setSlug('slugachanger');

            $user->setPassword($encoder->hashPassword($user, $user->getPassword()));

            $uploadFile = $form->get('picture')->getData();

                if ($uploadFile) {
                    $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();

                    $uploadFile->move(
                        $this->getParameter('upload_directory'),
                        $uploadedName
                    );
                
                    $user->setPicture($uploadedName);
                }
            $userRepository->add($user, true);

            return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('back_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserPasswordHasherInterface $encoder, UserRepository $userRepository): Response
    {
        $userFirstPicture = $user->getPicture();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new DateTime ('now'));
            $user->setPassword($encoder->hashPassword($user, $user->getPassword()));

            $uploadFile = $form->get('picture')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
                if ($uploadFile != null) {
                    $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();

                    $uploadFile->move(
                        $this->getParameter('upload_directory'),
                        $uploadedName
                    );
                
                    $user->setPicture($uploadedName);
                } else {
                    $user->setPicture($userFirstPicture);
                }
            $userRepository->add($user, true);

            return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
