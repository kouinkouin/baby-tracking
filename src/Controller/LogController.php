<?php

namespace App\Controller;

use App\Repository\BabyLogLineRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    private UserRepository $userRepository;

    private BabyLogLineRepository $babyLogLineRepository;

    public function __construct(UserRepository $userRepository, BabyLogLineRepository $babyLogLineRepository)
    {
        $this->userRepository = $userRepository;
        $this->babyLogLineRepository = $babyLogLineRepository;
    }

    /**
     * @Route("/log/add", name="log_add")
     */
    public function add(Request $request)
    {
        $username = $request->getUser();
        $user = $this->userRepository->findOneByUsername($username);

        $lastBabyLogLine = $this->babyLogLineRepository->findOneLastByUser($user);

        $babies = $user->getBabies();
        $preselectedBabyId = $lastBabyLogLine ? $lastBabyLogLine->getBaby()->getId() : $babies->first()->getId();

        $logTypes = [
            ['id' => 1, 'name' => 'Poids', 'icon' => 'fas fa-weight'],
            ['id' => 2, 'name' => 'Taille', 'icon' => 'fas fa-ruler-combined'],
            ['id' => 3, 'name' => 'Température', 'icon' => 'fas fa-thermometer'],
        ];
        $preselectedLogTypeId = $lastBabyLogLine ? $lastBabyLogLine->getTypeId() : 1;

        $now = (new DateTimeImmutable)->format('d/m/Y H:i');

        return $this->render(
            'log/add.html.twig',
            [
                'babies' => $babies,
                'preselected_baby_id' => $preselectedBabyId,
                'log_types' => $logTypes,
                'preselected_log_type_id' => $preselectedLogTypeId,
                'now' => $now,
            ]
        );
    }
}
