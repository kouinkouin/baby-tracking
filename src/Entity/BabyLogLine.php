<?php

namespace App\Entity;

use App\Repository\BabyLogLineRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BabyLogLineRepository::class)
 * @ORM\NamedNativeQueries({@ORM\NamedNativeQuery(name="lastOnes", resultClass="BabyLogLine", query="
SELECT bll1.*
FROM baby_log_line bll1
JOIN baby b ON bll1.baby_id = b.id
JOIN (
  SELECT bll.baby_id, bll.type_id, MAX(bll.when_dt) AS when_dt
  FROM baby_log_line bll
  GROUP BY bll.baby_id, bll.type_id
) AS bll0 ON bll0.baby_id = bll1.baby_id AND bll0.type_id = bll1.type_id AND bll0.when_dt = bll1.when_dt
JOIN map_user_baby mub ON mub.baby_id = b.id
WHERE mub.user_id = :user_id
")})
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
     *
     * @Assert\NotNull()
     */
    private ?Baby $baby;

    /**
     * @ORM\Column(type="datetime_immutable", name="when_dt")
     *
     * @Assert\NotBlank()
     */
    private ?DateTimeImmutable $when;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Assert\NotBlank()
     */
    private ?int $typeId;

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\NotBlank()
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

    public function getWhen(): ?DateTimeImmutable
    {
        return $this->when;
    }

    public function setWhen(DateTimeImmutable $when): self
    {
        $this->when = $when;

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
