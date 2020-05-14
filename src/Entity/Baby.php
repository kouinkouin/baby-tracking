<?php

namespace App\Entity;

use App\Repository\BabyRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=BabyLogLine::class, mappedBy="baby", orphanRemoval=true)
     */
    private $logLines;

    public function __construct()
    {
        $this->logLines = new ArrayCollection();
    }

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

    /**
     * @return Collection|BabyLogLine[]
     */
    public function getLogLines(): Collection
    {
        return $this->logLines;
    }

    public function addLogLine(BabyLogLine $logLine): self
    {
        if (!$this->logLines->contains($logLine)) {
            $this->logLines[] = $logLine;
            $logLine->setBaby($this);
        }

        return $this;
    }

    public function removeLogLine(BabyLogLine $logLine): self
    {
        if ($this->logLines->contains($logLine)) {
            $this->logLines->removeElement($logLine);
            // set the owning side to null (unless already changed)
            if ($logLine->getBaby() === $this) {
                $logLine->setBaby(null);
            }
        }

        return $this;
    }
}
