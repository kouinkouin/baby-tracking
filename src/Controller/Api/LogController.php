<?php

namespace App\Controller\Api;

use App\Repository\BabyLogLineRepository;
use App\Services\Helper\UserHelper;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function array_key_first;

class LogController extends AbstractController
{
    private const FORMAT_DATETIME_LOCAL = 'Y-m-d\TH:i';

    private UserHelper $userHelper;

    private BabyLogLineRepository $babyLogLineRepository;

    public function __construct(
        UserHelper $userHelper,
        BabyLogLineRepository $babyLogLineRepository
    ) {
        $this->userHelper = $userHelper;
        $this->babyLogLineRepository = $babyLogLineRepository;
    }

    /**
     * @Route("/api/log/add/fields", name="api_log_fields", methods={"GET"})
     */
    public function fieldsForAdd(Request $request)
    {
        if (!$user = $this->userHelper->getUserFromRequest($request)) {
            return $this->json(['errors' => ['you are not logged']], Response::HTTP_UNAUTHORIZED);
        }
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

        $selectedBabyId = $lastBabyLogLine ? $lastBabyLogLine->getBaby()->getId() : array_key_first($babies);

        $logTypes = [
            1 => ['name' => 'Poids', 'icon' => 'fa-weight'],
            2 => ['name' => 'Taille', 'icon' => 'fa-ruler-combined'],
            3 => ['name' => 'Température', 'icon' => 'fa-thermometer'],
            4 => ['name' => 'Tétée', 'icon' => 'fa-lemon'],
            5 => ['name' => 'Change', 'icon' => 'fa-toilet'],
        ];
        foreach (array_keys($logTypes) as $logTypeId) {
            $logType = $logTypes[$logTypeId];
            $logTypes[$logTypeId]['value'] = $logTypeId;
            $logTypes[$logTypeId]['html'] = sprintf('<i class="fas fa-fw %s"></i>', $logType['icon']);
            unset($logTypes[$logTypeId]['icon']);
        }
        $selectedLogTypeId = $lastBabyLogLine ? $lastBabyLogLine->getTypeId() : 1;

        $now = (new DateTimeImmutable)->format(self::FORMAT_DATETIME_LOCAL);

        $inputs = [
            1 => [
                ['name' => 'weight', 'text' => 'Poids', 'unit' => 'kg', 'type' => 'number'],
            ],
            2 => [
                ['name' => 'size', 'text' => 'Taille', 'unit' => 'cm', 'type' => 'number'],
            ],
            3 => [
                ['name' => 'temperature', 'text' => 'Température', 'unit' => '°C', 'type' => 'number'],
            ],
            4 => [
                [
                    'name' => 'duration',
                    'text' => 'Durée',
                    'type' => 'range',
                    'unit' => 'minutes',
                    'min' => 0,
                    'max' => 20,
                ],
                [
                    'name' => 'side',
                    'text' => 'Côté',
                    'type' => 'radio',
                    'choices' => [
                        ['text' => 'Droit', 'value' => 'right'],
                        ['text' => 'Gauche', 'value' => 'left'],
                        ['text' => 'Les deux', 'value' => 'both'],
                    ],
                ],
                [
                    'name' => 'end',
                    'text' => 'Fin',
                    'type' => 'radio',
                    'choices' => [
                        ['text' => 'Assoupi', 'value' => 1],
                        ['text' => 'Eveillé', 'value' => 2],
                    ],
                ],
            ],
            5 => [
                [
                    'name' => 'poo',
                    'text' => 'Caca',
                    'type' => 'radio',
                    'choices' => [
                        ['html' => '<i class="fas fa-fw fa-battery-empty"></i>', 'value' => 0],
                        ['html' => '<i class="fas fa-fw fa-battery-quarter"></i>', 'value' => 1],
                        ['html' => '<i class="fas fa-fw fa-battery-half"></i>', 'value' => 2],
                        ['html' => '<i class="fas fa-fw fa-battery-three-quarters"></i>', 'value' => 3],
                        ['html' => '<i class="fas fa-fw fa-battery-full"></i>', 'value' => 4],
                        ['html' => '<i class="fas fa-fw fa-fire"></i>', 'value' => 5],
                    ],
                ],
                [
                    'name' => 'pee',
                    'text' => 'Pipi',
                    'type' => 'radio',
                    'choices' => [
                        ['html' => '<i class="fas fa-fw fa-tint-slash"></i>', 'value' => 0],
                        ['html' => '<i class="fas fa-fw fa-tint"></i>', 'value' => 1],
                    ],
                ],
            ],
        ];

        return $this->json(
            [
                'babies' => $babies,
                'selectedBabyId' => $selectedBabyId,
                'logTypes' => $logTypes,
                'selectedLogTypeId' => $selectedLogTypeId,
                'now' => $now,
                'inputs' => $inputs,
            ]
        );
    }
}
