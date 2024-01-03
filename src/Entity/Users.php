<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activation_token = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientNom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientPrenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientMaritialStatus = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $patientDateDeNaissance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Sexe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientPhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientAdresse = null;

    #[ORM\Column(length: 255)]
    private ?string $patientEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reset_token = null;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = "ROLE_USER";

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): static
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getPatientNom(): ?string
    {
        return $this->patientNom;
    }

    public function setPatientNom(string $patientNom): static
    {
        $this->patientNom = $patientNom;

        return $this;
    }

    public function getPatientPrenom(): ?string
    {
        return $this->patientPrenom;
    }

    public function setPatientPrenom(string $patientPrenom): static
    {
        $this->patientPrenom = $patientPrenom;

        return $this;
    }

    public function getPatientMaritialStatus(): ?string
    {
        return $this->patientMaritialStatus;
    }

    public function setPatientMaritialStatus(string $patientMaritialStatus): static
    {
        $this->patientMaritialStatus = $patientMaritialStatus;

        return $this;
    }

    public function getPatientDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->patientDateDeNaissance;
    }

    public function setPatientDateDeNaissance(\DateTimeInterface $patientDateDeNaissance): static
    {
        $this->patientDateDeNaissance = $patientDateDeNaissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->Sexe;
    }

    public function setSexe(string $Sexe): static
    {
        $this->Sexe = $Sexe;

        return $this;
    }

    public function getPatientPhone(): ?string
    {
        return $this->patientPhone;
    }

    public function setPatientPhone(string $patientPhone): static
    {
        $this->patientPhone = $patientPhone;

        return $this;
    }

    public function getPatientAdresse(): ?string
    {
        return $this->patientAdresse;
    }

    public function setPatientAdresse(string $patientAdresse): static
    {
        $this->patientAdresse = $patientAdresse;

        return $this;
    }

    public function getPatientEmail(): ?string
    {
        return $this->patientEmail;
    }

    public function setPatientEmail(string $patientEmail): static
    {
        $this->patientEmail = $patientEmail;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): static
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setUsers($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getUsers() === $this) {
                $appointment->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }
}
