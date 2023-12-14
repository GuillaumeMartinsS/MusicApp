<?php

namespace App\DataFixtures;

use App\Entity\Banner;
use App\Entity\Genre;
use App\Entity\Song;
use App\Entity\Playlist;
use App\Entity\Review;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory as Faker;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $connexion;
    private $hasher;
    private $slugger;

    public function __construct(Connection $connexion, UserPasswordHasherInterface $hasher, SluggerInterface $slugger)
    {
        $this->connexion = $connexion;
        $this->hasher = $hasher;
        $this->slugger = $slugger;
    }

    private function truncate()
    {
        $this->connexion->executeQuery('SET foreign_key_checks = 0');

        $this->connexion->executeQuery('TRUNCATE TABLE banner');
        $this->connexion->executeQuery('TRUNCATE TABLE genre');
        $this->connexion->executeQuery('TRUNCATE TABLE song');
        $this->connexion->executeQuery('TRUNCATE TABLE song_genre');
        $this->connexion->executeQuery('TRUNCATE TABLE song_playlist');
        $this->connexion->executeQuery('TRUNCATE TABLE playlist');
        $this->connexion->executeQuery('TRUNCATE TABLE review');
        $this->connexion->executeQuery('TRUNCATE TABLE user');
        $this->connexion->executeQuery('TRUNCATE TABLE user_song');
        $this->connexion->executeQuery('TRUNCATE TABLE user_user');
    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->truncate();

        $faker = Faker::create('fr_FR');

        /************* Genre *************/

        // Making of data fixtures for Genre Entity :
            $allGenreEntity = [];
            $genreNames = [
                'Pop', 'Rock', 'Hip-Hop', 'RNB', 'Electro', 'Dance', 'Soul', 'Funk', 'Classique', 'Jazz', 'Ambient', 'Expérimentale'
            ];
            
            foreach ($genreNames as $genreName) {
    
                $newGenre = new Genre();
                $newGenre->setName($genreName);
    
                $newGenre->setPicture('https://picsum.photos/id/'.mt_rand(1, 100).'/303/424');
                $newGenre->setDescription($faker->realText($maxNbChars = 50, $indexSize = 2));
                $newGenre->setStatus(1);
                dump($genreName);
                $newGenre->setSlug($this->slugger->slug($newGenre->getName()));
    
                $newGenre->setCreatedAt(new DateTimeImmutable('now'));
    
                $allGenreEntity[] = $newGenre;
    
                $manager->persist($newGenre);
            } 
    
    
            /************* User *************/
            // Making of data fixtures for User Entity :
    
            $users = [
                [
                    'name' => 'Romain',
                    'email' => 'romain@romain.com',
                    'password' => 'romain',
                    'certification' => true,
                    'roles' => 'ROLE_ADMIN',
                    'status' => 1,         
                ],
                [
                    'name' => 'Guillaume',
                    'email' => 'guillaume@guillaume.com',
                    'password' => 'guillaume',
                    'certification' => true,
                    'roles' => 'ROLE_ADMIN',
                    'status' => 1,
                ],
                [
                    'name' => 'Maxime',
                    'email' => 'maxime@maxime.com',
                    'password' => 'maxime',
                    'certification' => true,
                    'roles' => 'ROLE_ADMIN',
                    'status' => 1,
                ],
                [
                    'name' => 'Edouard',
                    'email' => 'edouard@edouard.com',
                    'password' => 'edouard',
                    'certification' => true,
                    'roles' => 'ROLE_ADMIN',
                    'status' => 1,
                ],
                [
                    'name' => 'Audrey',
                    'email' => 'audrey@audrey.com',
                    'password' => 'audrey',
                    'certification' => true,
                    'roles' => 'ROLE_ADMIN',
                    'status' => 1,
                ],
                [
                    'name' => 'Mickael',
                    'email' => 'mickael@mickael.com',
                    'password' => 'mickael',
                    'certification' => true,
                    'roles' => 'ROLE_ADMIN',
                    'status' => 1,
                ],
                [
                    'name' => 'Anne',
                    'email' => 'anne@anne.com',
                    'password' => 'anne',
                    'certification' => false,
                    'roles' => 'ROLE_USER',
                    'status' => 1,
                ]
            ];
    
            $allUserEntity = [];
    
            foreach ($users as $currentUser)
            {
    
                $newUser = new User();
                $newUser->setName($currentUser['name']);
                $newUser->setEmail($currentUser['email']);
    
                $hashedPassword = $this->hasher->hashPassword(
                    $newUser,
                    $currentUser['password']
                );
                $newUser->setPassword($hashedPassword);
                $newUser->setPicture('https://picsum.photos/id/'.mt_rand(1, 100).'/303/424');
                $newUser->setDescription($faker->realText($maxNbChars = 50, $indexSize = 2));
                $newUser->setCertification($currentUser['certification']);
                $newUser->setRoles([$currentUser['roles']]);
                $newUser->setStatus($currentUser['status']);
                $newUser->setLabel($faker->lastName());
                // $userSlug = $this->mySlugger->slugify($newUser->getName());
                $newUser->setSlug($this->slugger->slug($newUser->getName()));
                $newUser->setCreatedAt(new DateTimeImmutable('now'));
    
                $allUserEntity[] =$newUser;
    
                $manager->persist($newUser);
            }
    
            
            // TODO /************* Users Relation *************/
    
            for ($i = 0; $i<= count($allUserEntity) - 1; $i++)
            {
                $currentUser = $allUserEntity[$i];
    
                for ($j = 1; $j < 2; $j++) 
                {
        
                    $randomUser = $allUserEntity[mt_rand(0, count($allUserEntity) - 1)];
        
                    if($randomUser->getEmail() === $currentUser->getEmail())
                    {
                        break;
                    }
        
                    else 
                    {
                        $currentUser->addSubscription($randomUser);
                        $randomUser->addSubcriber($currentUser);
                    }
                }
        
                // for ($j = 1; $j < rand(1, 5); $j++) 
                // {
        
                //     $randomUser = $allUserEntity[mt_rand(0, count($allUserEntity) - 1)];
        
                //     if($randomUser->getEmail() === $currentUser->getEmail())
                //     {
                //         break;
                //     }
        
                //     else 
                //     {
                //         $currentUser->addSubcriber($randomUser);
                //     }
                // }
    
            $manager->persist($currentUser);

            }
    
    
            /************* Song *************/
    
            $allSongEntity = [];
            $songFiles = [
                'https://www.youtube.com/watch?v=7yh9i0PAjck', 'https://www.youtube.com/watch?v=U2wtIIT9hMU', 'https://www.youtube.com/watch?v=IRvGZffXhfk', 'https://www.youtube.com/watch?v=YnopHCL1Jk8', 'https://www.youtube.com/watch?v=U6n2NcJ7rLc', 'https://www.youtube.com/watch?v=bpEmjxobvbY'
            ];
    
    
            for ($i = 1; $i<= 20; $i++)
            {
                $newSong = new Song();
                $newSong->setTitle($faker->sentence(rand(1,4)));
                $newSong->setFile($songFiles[mt_rand(0, count($songFiles) - 1)]);
                $newSong->setPicture('https://picsum.photos/id/'.mt_rand(1, 100).'/303/424');
                $newSong->setDescription($faker->realText($maxNbChars = 25, $indexSize = 2));
                $newSong->setStatus(1);
                $newSong->setReleaseDate($faker->dateTimeBetween('-20years', 'now'));
                $newSong->setNbLike(rand(1,1000));
                $newSong->setNbListened(rand(1,1000000));
                // $songSlug = $this->mySlugger->slugify($newSong->getTitle());
                // $newSong->setSlug($songSlug);
        
                $newSong->setCreatedAt(new DateTimeImmutable('now'));
        
                for ($j = 1; $j<= rand(1, rand(1,2)); $j++) 
                {
                    $randomUser = $allUserEntity[mt_rand(0, count($allUserEntity) - 1)];
                    $newSong->addUser($randomUser);
                }
    
                for ($j = 1; $j<= rand(1, 3); $j++) 
                {
                    $randomGenre = $allGenreEntity[mt_rand(0, count($allGenreEntity) - 1)];
                    $newSong->addGenre($randomGenre);
                }
        
                $allSongEntity[] = $newSong;
    
                $manager->persist($newSong);
            }
    
    
            /************* Playlist*************/
    
            $allPlaylistEntity = [];
    
            for ($i = 1; $i<= 20; $i++)
            {
                $newPlaylist = new Playlist();
                
                $newPlaylist->setName($faker->word());
                $newPlaylist->setPicture('https://picsum.photos/id/'.mt_rand(1, 100).'/303/424');
                $newPlaylist->setDescription($faker->realText($maxNbChars = 50, $indexSize = 2));
                $newPlaylist->setAlbum(rand(0,1));
                $newPlaylist->setStatus(1);
                $newPlaylist->setNbLike(rand(1,1000));

                $newPlaylist->setSlug($this->slugger->slug($newPlaylist->getName()));
    
                $newPlaylist->setCreatedAt(new DateTimeImmutable('now'));
    
                $randomUser = $allUserEntity[mt_rand(0, count($allUserEntity) - 1)];
                $newPlaylist->setUser($randomUser);
                
                for ($j = 1; $j<= rand(1, 40); $j++) 
                {
                    $randomSong = $allSongEntity[mt_rand(0, count($allSongEntity) - 1)];
                    $newPlaylist->addSong($randomSong);
                }
    
                $allPlaylistEntity[] = $newPlaylist;
    
                $manager->persist($newPlaylist);            
            }
    
    
            /************* Review *************/
    
            $allReviewEntity = [];      
    
            for ($i = 1; $i<= 20; $i++) 
            {
                
                $newReview = new Review();
                $newReview->setTitle($faker->word());
                $newReview->setContent($faker->realText($maxNbChars = 100, $indexSize = 2));
                $newReview->setStatus(1);
                $newReview->setCreatedAt(new DateTimeImmutable('now'));
    
                $randomUser = $allUserEntity[mt_rand(0, count($allUserEntity) - 1)];
                $newReview->setUser($randomUser);
    
    
                $randomSong = $allSongEntity[mt_rand(0, count($allSongEntity) - 1)];
                $newReview->setSong($randomSong);
    
                $allReviewEntity[] = $newReview;
    
                $manager->persist($newReview);
            }
    
    
            /************* Banner *************/ 
    
            for ($i = 1; $i<= 5; $i++) 
            {
                
                $newBanner = new Banner();
                $newBanner->setName('https://picsum.photos/id/'.mt_rand(3, 300).'/303/424');
                $newBanner->setStatus(1);
                $newBanner->setCreatedAt(new DateTimeImmutable('now'));
                $manager->persist($newBanner);
            }

        $manager->flush();
    }
}
