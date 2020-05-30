<?php

namespace App\Services\Helper;

class LogTypeHelper
{
    const ID_WEIGHT = 1;
    const ID_SIZE = 2;
    const ID_TEMPERATURE = 3;
    const ID_BREAST_FEED = 4;
    const ID_CHANGE = 5;

    private array $logTypes = [
        self::ID_WEIGHT => ['name' => 'Poids', 'icon' => 'fa-weight'],
        self::ID_SIZE => ['name' => 'Taille', 'icon' => 'fa-ruler-combined'],
        self::ID_TEMPERATURE => ['name' => 'Température', 'icon' => 'fa-thermometer'],
        self::ID_BREAST_FEED => ['name' => 'Tétée', 'icon' => 'fa-lemon'],
        self::ID_CHANGE => ['name' => 'Change', 'icon' => 'fa-toilet'],
    ];

    public function getAll(): array
    {
        $logTypes = $this->logTypes;

        foreach (array_keys($logTypes) as $logTypeId) {
            $logType = $logTypes[$logTypeId];
            $logTypes[$logTypeId]['value'] = $logTypeId;
            $logTypes[$logTypeId]['html'] = sprintf('<i class="fas fa-fw %s"></i>', $logType['icon']);
            unset($logTypes[$logTypeId]['icon']);
        }

        return $logTypes;
    }

    public function getInputs(): array
    {
        return [
            self::ID_WEIGHT => [
                ['name' => 'weight', 'text' => 'Poids', 'unit' => 'kg', 'type' => 'number'],
            ],
            self::ID_SIZE => [
                ['name' => 'size', 'text' => 'Taille', 'unit' => 'cm', 'type' => 'number'],
            ],
            self::ID_TEMPERATURE => [
                ['name' => 'temperature', 'text' => 'Température', 'unit' => '°C', 'type' => 'number'],
            ],
            self::ID_BREAST_FEED => [
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
                        ['text' => 'Gauche', 'value' => 'left'],
                        ['text' => 'Droit', 'value' => 'right'],
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
                [
                    'name' => 'regurgitation',
                    'text' => 'Régurgitation',
                    'type' => 'radio',
                    'choices' => [
                        ['text' => 'Non', 'value' => 0],
                        ['text' => 'Oui', 'value' => 1],
                    ],
                ],
            ],
            self::ID_CHANGE => [
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
                [
                    'name' => 'regurgitation',
                    'text' => 'Régurgitation',
                    'type' => 'radio',
                    'choices' => [
                        ['text' => 'Non', 'value' => 0],
                        ['text' => 'Oui', 'value' => 1],
                    ],
                ],
            ],
        ];
    }
}
