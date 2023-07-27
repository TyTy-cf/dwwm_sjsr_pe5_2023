<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetUsernameController extends AbstractController
{

    public function __invoke(string $name): User
    {
        dd($name);
    }

}
