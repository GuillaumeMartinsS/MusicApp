<?php

namespace App\EventListener;

use App\Entity\Playlist;
use App\Service\MySlugger;

class PlaylistListener
{
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;    
    }

    public function updatePlaylist(Playlist $playlist)
    {
        $slug = $this->slugger->slugify($playlist->getName());
        $playlist->setSlug($slug);
    }
}