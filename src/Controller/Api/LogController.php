<?php

namespace App\Controller\Api;

use App\Repository\BabyLogLineRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use function array_key_first;

class LogController extends AbstractController
{
    private const FORMAT_DATETIME_LOCAL = 'Y-m-d\TH:i';

    private UserRepository $userRepository;

    private BabyLogLineRepository $babyLogLineRepository;

    public function __construct(
        UserRepository $userRepository,
        BabyLogLineRepository $babyLogLineRepository
    ) {
        $this->userRepository = $userRepository;
        $this->babyLogLineRepository = $babyLogLineRepository;
    }

    /**
     * @Route("/api/log/add/fields", name="api_log_fields", methods={"GET"})
     */
    public function fieldsForAdd(Request $request)
    {
        $username = $request->getUser();
        dump($request);
        $user = $this->userRepository->findOneByUsername($username);
        $babies = [];
        foreach ($user->getBabies() as $baby) {
            $babies[$baby->getId()] = [
                'value' => $baby->getId(),
                'text' => $baby->getName(),
            ];
        }
        if (!$babies) {
            return $this->json(
                ['errors' => ['You need almost one baby registered (and there is not URL for that yet!)']],
                500
            );
        }

        $lastBabyLogLine = $this->babyLogLineRepository->findOneLastByUser($user);

        $preselectedBabyId = $lastBabyLogLine ? $lastBabyLogLine->getBaby()->getId() : array_key_first($babies);

        $logTypes = [
            ['value' => 1, 'text' => 'Poids', 'icon' => 'fas fa-weight'],
            ['value' => 2, 'text' => 'Taille', 'icon' => 'fas fa-ruler-combined'],
            ['value' => 3, 'text' => 'Température', 'icon' => 'fas fa-thermometer'],
        ];
        $preselectedLogTypeId = $lastBabyLogLine ? $lastBabyLogLine->getTypeId() : 1;

        $now = (new DateTimeImmutable)->format(self::FORMAT_DATETIME_LOCAL);

        return $this->json(
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
