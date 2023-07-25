<?php

namespace App\Service\Entity;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) { }

    /**
     * @param User $user
     * @return User
     */
    public function encodeUserPassword(User $user): User {
        return $user->setPassword(
            $this->hasher->hashPassword($user, $user->getPassword())
        );
    }

}
