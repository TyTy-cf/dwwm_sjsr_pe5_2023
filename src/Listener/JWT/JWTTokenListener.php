<?php

namespace App\Listener\JWT;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTTokenListener
{

    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $userInterface = $event->getUser();

        $user = $this->userRepository->findOneBy([
            'email' => $userInterface->getUserIdentifier()
        ]);

        $payload = $event->getData();
        unset($payload['roles']);

        if ($user) {
            $payload['admin'] = $user->isAdmin();
            $payload['id'] = $user->getId();
        }

        $event->setData($payload);

        $header        = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }

}
