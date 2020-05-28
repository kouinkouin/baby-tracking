<?php

namespace App\Services\Helper;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class UserHelper
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserFromRequest(Request $request): ?User
    {
        $username = $request->getUser();

        return $this->userRepository->findOneByUsername($username);
    }
}
