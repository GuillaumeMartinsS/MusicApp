<?php

namespace App\Controller;

use App\Entity\Review;
use App\Models\JsonError;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiReviewController extends AbstractController
{
    /**
     * @Route("/api/reviews", name="api_review", methods={"GET"})
     */
    public function listReview(ReviewRepository $reviewRepository): Response
    {
        return $this->json(
            $reviewRepository->findAll(),
            200,
            [],
            ['groups'=> ['list_review']]
        );
    }

    /**
     * @Route("/api/reviews/{id}", name="api_review_id", methods={"GET"})
     */
    public function showReview(Review $review = null)
    {
        if ($review === null){
            $error = new JsonError(Response::HTTP_NOT_FOUND, Review::class . ' non trouvé');
            return $this->json($error, $error->getError());
        }

        return $this->json(
            $review,
            Response::HTTP_OK,
            [],
            ['groups' => 'show_review']
        );
    }
}
