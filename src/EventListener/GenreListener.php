<?php

namespace App\EventListener;

use App\Entity\Genre;
use App\Service\MySlugger;

class GenreListener
{
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;    
    }

    public function updateGenre(Genre $genre)
    {
        $slug = $this->slugger->slugify($genre->getName());
        $genre->setSlug($slug);
    }
}