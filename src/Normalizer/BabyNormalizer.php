<?php

namespace App\Normalizer;

use App\Entity\Baby;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BabyNormalizer implements NormalizerInterface
{
    private ?ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, string $format = null, array $context = array()): array
    {
        return $this->normalizer->normalize(
            $object,
            $format,
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['logLines']]
        );
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Baby;
    }
}
