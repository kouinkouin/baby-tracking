<?php

namespace App\Entity;

use App\Repository\BabyLogLineRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BabyLogLineRepository::class)
 */
class BabyLogLine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $id;

    /**
     * @ORM\ManyToOne(targetEntity=Baby::class, inversedBy="logLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Baby $baby;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $creationDatetime;

    /**
     * @ORM\Column(type="smallint")
     */
    private ?int $typeId;

    /**
     * @ORM\Column(type="json")
     */
    private array $data = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBaby(): ?Baby
    {
        return $this->baby;
    }

    public function setBaby(?Baby $baby): self
    {
        $this->baby = $baby;

        return $this;
    }

    public function getCreationDatetime(): ?DateTimeImmutable
    {
        return $this->creationDatetime;
    }

    public function setCreationDatetime(DateTimeImmutable $creationDatetime): self
    {
        $this->creationDatetime = $creationDatetime;

        return $this;
    }

    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    public function setTypeId(int $typeId): self
    {
        $this->typeId = $typeId;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
