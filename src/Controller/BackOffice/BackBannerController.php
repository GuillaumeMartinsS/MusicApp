<?php

namespace App\Controller\BackOffice;

use DateTime;
use App\Entity\Banner;
use DateTimeImmutable;
use App\Form\BannerType;
use App\Repository\BannerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/banner")
 */
class BackBannerController extends AbstractController
{
    /**
     * @Route("/", name="app_back_banner_index", methods={"GET"})
     */
    public function index(BannerRepository $bannerRepository): Response
    {
        return $this->render('back_banner/index.html.twig', [
            'banners' => $bannerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_banner_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BannerRepository $bannerRepository): Response
    {
        $banner = new Banner();
        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $banner->setCreatedAt(new DateTimeImmutable('now'));
            $bannerRepository->add($banner, true);

            return $this->redirectToRoute('app_back_banner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_banner/new.html.twig', [
            'banner' => $banner,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_banner_show", methods={"GET"})
     */
    public function show(Banner $banner): Response
    {
        return $this->render('back_banner/show.html.twig', [
            'banner' => $banner,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_banner_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Banner $banner, BannerRepository $bannerRepository): Response
    {
        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $banner->setUpdatedAt(new DateTime ('now'));
            $bannerRepository->add($banner, true);

            return $this->redirectToRoute('app_back_banner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_banner/edit.html.twig', [
            'banner' => $banner,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_banner_delete", methods={"POST"})
     */
    public function delete(Request $request, Banner $banner, BannerRepository $bannerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banner->getId(), $request->request->get('_token'))) {
            $bannerRepository->remove($banner, true);
        }

        return $this->redirectToRoute('app_back_banner_index', [], Response::HTTP_SEE_OTHER);
    }
}
