<?php

namespace App\Controller;

use App\Entity\Review;
use DateTimeImmutable;
use App\Models\JsonError;
use App\Repository\SongRepository;
use App\Repository\UserRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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

    /**
     * @Route("/api/reviews", name="api_review_create", methods={"POST"})
     */
    public function createReview(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, SongRepository $songRepository)
    {
        $newReview = new Review();

        $data = $request->getContent();
        $dataDecoded = json_decode($data);

        $newReview->setTitle($dataDecoded->title);
        $newReview->setContent($dataDecoded->content);
        $newReview->setStatus(1);
        $newReview->setCreatedAt(new DateTimeImmutable('now'));

        $newReview->setUser($this->getUser());

        $newReview->setSong($songRepository->find($dataDecoded->song));

        $entityManager->persist($newReview);
        $entityManager->flush();

        return $this->json(
            $newReview,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_review']]
        );
    }

    /**
     * @Route("/api/reviews/delete/{id}", name="api_review_delete", methods={"POST"})
     */
    public function delete(Request $request, Review $review, ReviewRepository $ReviewRepository)
    {
        $ReviewRepository->remove($review, true);

        return $this->json(
            'La Review a bien été supprimée',
            Response::HTTP_CREATED,
            [],
            []
        );
        
    }
}
