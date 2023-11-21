<?php

namespace App\EventListener;

use App\Entity\Song;
use App\Service\MySlugger;

class SongListener
{
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;    
    }

    public function updateSong(Song $song)
    {
        $slug = $this->slugger->slugify($song->getTitle());
        $song->setSlug($slug);
        // $song->setStatus(1);
        // $song->setCreatedAt(new DateTimeImmutable('now'));
    }

}