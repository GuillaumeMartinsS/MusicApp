<?php

// src/App/EventListener/JWTCreatedListener.php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var $user \AppBundle\Entity\User */
        $user = $event->getUser();

        // add new data
        $payload['id'] = $user->getId();
        $payload['email'] = $user->getEmail();
        $payload['name'] = $user->getName();
        $payload['roles'] = $user->getRoles([]);
        $payload['slug'] = $user->getSlug();
        $event->setData($payload);
    }
}