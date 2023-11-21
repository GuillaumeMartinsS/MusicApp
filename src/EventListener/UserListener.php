<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\MySlugger;

class UserListener
{
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;    
    }

    public function updateUser(User $user)
    {
        $slug = $this->slugger->slugify($user->getName());
        $user->setSlug($slug);
    }
}