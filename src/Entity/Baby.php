<?php

namespace App\Entity;

use App\Repository\BabyRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BabyRepository::class)
 */
class Baby
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $birthDatetime;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBirthDatetime(): ?DateTimeImmutable
    {
        return $this->birthDatetime;
    }

    public function setBirthDatetime(?DateTimeImmutable $birthDatetime): self
    {
        $this->birthDatetime = $birthDatetime;

        return $this;
    }
}
