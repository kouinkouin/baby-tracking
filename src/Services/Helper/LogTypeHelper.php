<?php

namespace App\Services\Helper;

class LogTypeHelper
{
    const ID_WEIGHT = 1;
    const ID_SIZE = 2;
    const ID_TEMPERATURE = 3;
    const ID_BREAST_FEED = 4;
    const ID_CHANGE = 5;
    const ID_VOMIT = 6;

    private array $logTypes = [
        self::ID_WEIGHT => ['name' => 'Poids', 'icon' => 'fa-weight'],
        self::ID_SIZE => ['name' => 'Taille', 'icon' => 'fa-ruler-combined'],
        self::ID_TEMPERATURE => ['name' => 'Température', 'icon' => 'fa-thermometer'],
        self::ID_BREAST_FEED => ['name' => 'Tétée', 'icon' => 'fa-lemon'],
        self::ID_CHANGE => ['name' => 'Change', 'icon' => 'fa-toilet'],
        self::ID_VOMIT => ['name' => 'Vomi', 'icon' => 'fa-splotch'],
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
                'weight' => ['name' => 'weight', 'text' => 'Poids', 'unit' => 'kg', 'type' => 'number'],
            ],
            self::ID_SIZE => [
                'size' => ['name' => 'size', 'text' => 'Taille', 'unit' => 'cm', 'type' => 'number'],
            ],
            self::ID_TEMPERATURE => [
                'temperature' => ['name' => 'temperature', 'text' => 'Température', 'unit' => '°C', 'type' => 'number'],
            ],
            self::ID_BREAST_FEED => [
                'duration' => [
                    'name' => 'duration',
                    'text' => 'Durée',
                    'type' => 'range',
                    'unit' => 'minutes',
                    'min' => 0,
                    'max' => 20,
                ],
                'side' => [
                    'name' => 'side',
                    'text' => 'Côté',
                    'type' => 'radio',
                    'choices' => [
                        'left' => ['text' => 'Gauche', 'value' => 'left'],
                        'right' => ['text' => 'Droit', 'value' => 'right'],
                        'both' => ['text' => 'Les deux', 'value' => 'both'],
                    ],
                ],
                'end' => [
                    'name' => 'end',
                    'text' => 'Fin',
                    'type' => 'radio',
                    'choices' => [
                        1 => ['text' => 'Assoupi', 'value' => 1],
                        2 => ['text' => 'Eveillé', 'value' => 2],
                    ],
                ],
                'regurgitation' => [
                    'name' => 'regurgitation',
                    'text' => 'Régurgitation',
                    'type' => 'radio',
                    'choices' => [
                        0 => ['text' => 'Non', 'value' => 0],
                        1 => ['text' => 'Oui', 'value' => 1],
                    ],
                ],
            ],
            self::ID_CHANGE => [
                'poo' => [
                    'name' => 'poo',
                    'text' => 'Caca',
                    'type' => 'radio',
                    'choices' => [
                        0 => ['html' => '<i class="fas fa-fw fa-battery-empty"></i>', 'value' => 0],
                        1 => ['html' => '<i class="fas fa-fw fa-battery-quarter"></i>', 'value' => 1],
                        2 => ['html' => '<i class="fas fa-fw fa-battery-half"></i>', 'value' => 2],
                        3 => ['html' => '<i class="fas fa-fw fa-battery-three-quarters"></i>', 'value' => 3],
                        4 => ['html' => '<i class="fas fa-fw fa-battery-full"></i>', 'value' => 4],
                        5 => ['html' => '<i class="fas fa-fw fa-fire"></i>', 'value' => 5],
                    ],
                ],
                'pee' => [
                    'name' => 'pee',
                    'text' => 'Pipi',
                    'type' => 'radio',
                    'choices' => [
                        0 => ['html' => '<i class="fas fa-fw fa-tint-slash"></i>', 'value' => 0],
                        1 => ['html' => '<i class="fas fa-fw fa-tint"></i>', 'value' => 1],
                    ],
                ],
                'regurgitation' => [
                    'name' => 'regurgitation',
                    'text' => 'Régurgitation',
                    'type' => 'radio',
                    'choices' => [
                        0 => ['text' => 'Non', 'value' => 0],
                        1 => ['text' => 'Oui', 'value' => 1],
                    ],
                ],
            ],
            self::ID_VOMIT => [
                'vomit_degree' => [
                    'name' => 'vomit_degree',
                    'text' => 'Degré de vomi',
                    'type' => 'radio',
                    'choices' => [
                        1 => ['text' => 'Petite régurgitation', 'value' => 1],
                        2 => ['text' => 'Bonne régurgitation', 'value' => 2],
                        3 => ['text' => 'Vomi', 'value' => 3],
                    ],
                ],
            ],
        ];
    }
}
