<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Symptome = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Comments = null;

    #[ORM\Column]
    private ?bool $Status = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Schedule $schedule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymptome(): ?string
    {
        return $this->Symptome;
    }

    public function setSymptome(?string $Symptome): static
    {
        $this->Symptome = $Symptome;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->Comments;
    }

    public function setComments(?string $Comments): static
    {
        $this->Comments = $Comments;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->Status;
    }

    public function setStatus(bool $Status): static
    {
        $this->Status = $Status;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }
}
