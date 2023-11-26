<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use DateTimeImmutable;
use App\Models\JsonError;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_user", methods={"GET"})
     */
    public function listUser(UserRepository $userRepository): Response
    {
        // dd($userRepository);
        return $this->json(
            $userRepository->findAll(),
            200,
            [],
            ['groups'=> ['list_user']]
        );
    }

    /**
     * @Route("/api/users/{id}", name="api_user_id", methods={"GET"})
     */
    public function showUser(User $user = null)
    {
        // dump($user);
        if ($user === null){
            $error = new JsonError(Response::HTTP_NOT_FOUND, User::class . ' non trouvé');
            return $this->json($error, $error->getError());
        }

        return $this->json(
            $user,
            Response::HTTP_OK,
            [],
            ['groups' => 'show_user']
        );
    }

    /**
     * @Route("/api/users", name="api_user_create", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function createUser(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $hasher)
    {       

        $data = $request->getContent();
        $dataDecoded = json_decode($data);

        $newUser = new User;

        $newUser->setName($dataDecoded->name);
        $newUser->setEmail($dataDecoded->email);
        $newUser->setPassword($dataDecoded->password);
        $newUser->setLabel($dataDecoded->label);

        $newUser->setPassword($hasher->hashPassword($newUser, $newUser->getPassword()));
        
        $errors = $validator->validate($newUser);
        if (count($errors) > 0) {

            $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
            $myJsonError->setValidationErrors($errors);
    
            return $this->json($myJsonError, $myJsonError->getError());
        }

        // now it's an event
        // $newUser->setSlug($dataDecoded->slug);

        $newUser->setCertification(false);
        // dump($newUser);
        $newUser->setStatus(1);
        // dump($newUser);
        $newUser->setRoles(["ROLE_USER"]);
        $newUser->setCreatedAt(new DateTimeImmutable('now'));
        // dump($newUser);

        $entityManager->persist($newUser);
        $entityManager->flush();

        return $this->json(
            $newUser,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_user']]
        );
    }

    /**
     * @Route("/api/users/edit/{id}", name="api_user_edit", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function updateUser(Request $request, User $user, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        if ($request->request->get('name')!== null) {
            $user->setName($request->request->get('name'));
        }

        if ($request->request->get('email')!== null) {
        $user->setEmail($request->request->get('email'));
        }

        if ($request->request->get('password')!== null) {
        $user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
        }

        if ($request->request->get('description')!== null) {
        $user->setDescription($request->request->get('description'));
        }

        $user->setUpdatedAt(new DateTime ('now'));

        if ($request->request->get('label')!== null) {
            $user->setLabel($request->request->get('label'));
        }

        if ($request->files!== null) {
        $upload = $request->files;
        

            foreach ($upload as $key => $uploadFile)
            {
                $uploadedName = md5(uniqid()) . '.' . $uploadFile->guessExtension();
                
                // we validate it with validator constraints  , if are picture really is an image file  
                // dd($errors = $validator->validate($uploadFile, new Image([]) ));
                $errors = $validator->validate($uploadFile, new Image([]));

                //if errors are found, we send a Json error
                if (count($errors) > 0) {

                    $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erapi_user_randreurs de validation ont été trouvées");
                    $myJsonError->setValidationErrors($errors);
            
                    return $this->json($myJsonError, $myJsonError->getError());
                }

                // if no error is found, we move the file to the upload_directory setting on the services.yaml file
                $uploadFile->move(
                    $this->getParameter('upload_directory'),
                    $uploadedName
                );
                // dump($uploadFile);
                // dd($uploadedName);

                // then its name goes as a value for the picture property
                $user->setPicture($uploadedName);

            }
        }

        // dump($uploadFile);

        // dd($errors = $validator->validate($uploadFile, new Image([
        //     'mimeTypes' => [
        //         "image/jpeg",
        //         "image/jpg",
        //         "image/png",
        //         "image/gif",
        //     ],
        // ]) ));namelidator->validate($uploadFile, new Image([
        //     'mimeTypes' => [
        //         "image/jpeg",
        //         "image/jpg",
        //         "image/png",
        //         "image/gif",
        //     ],
        // ]) );

        // * @Assert\Image(
        //     *     mimeTypes = {"image/jpeg",
        //     *                  "image/jpg",
        //     *                  "image/png",
        //     *                  "image/gif",
        //     * },)


        // dd($validator->validate($user));

        // $errors = $validator->validate($user);
        // $errors = $validator->validate($user, new Image([
        //     'maxSize' => "10k",
        //     'minWidth' => 200,
        //     'maxWidth' => 5000,
        //     'minHeight' => 200,
        //     'maxHeight' => 5000,
        //     'mimeTypes' => [
        //         "image/jpeg",
        //         "image/jpg",
        //         "image/png",
        //         "image/gif",
        //     ],
        // ]));

        // dd($errors);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(
            $user,
            201,
            [],
            ['groups' => ['show_user']]
        );

    }
}
