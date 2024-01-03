<?php

namespace App\Entity;

use App\Repository\DateSearchRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DateSearchRepository::class)]
class DateSearch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateSearch1 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSearch1(): ?\DateTimeInterface
    {
        return $this->dateSearch1;
    }

    public function setDateSearch1(\DateTimeInterface $dateSearch1): static
    {
        $this->dateSearch1 = $dateSearch1;

        return $this;
    }
}
