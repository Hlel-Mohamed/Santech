<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $ScheduleDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ScheduleDay = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $StartTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $EndTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $BookAvail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScheduleDate(): ?\DateTimeInterface
    {
        return $this->ScheduleDate;
    }

    public function setScheduleDate(\DateTimeInterface $ScheduleDate): static
    {
        $this->ScheduleDate = $ScheduleDate;

        return $this;
    }

    public function getScheduleDay(): ?string
    {
        return $this->ScheduleDay;
    }

    public function setScheduleDay(?string $ScheduleDay): static
    {
        $this->ScheduleDay = $ScheduleDay;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->StartTime;
    }

    public function setStartTime(?\DateTimeInterface $StartTime): static
    {
        $this->StartTime = $StartTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->EndTime;
    }

    public function setEndTime(?\DateTimeInterface $EndTime): static
    {
        $this->EndTime = $EndTime;

        return $this;
    }

    public function getBookAvail(): ?string
    {
        return $this->BookAvail;
    }

    public function setBookAvail(?string $BookAvail): static
    {
        $this->BookAvail = $BookAvail;

        return $this;
    }
}
