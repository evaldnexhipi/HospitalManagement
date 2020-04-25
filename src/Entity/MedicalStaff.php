<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MedicalStaffRepository")
 */
class MedicalStaff
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="medicalStaff", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hall", inversedBy="medicalStaff")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hall;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Schedule", mappedBy="medicalStaff")
     */
    private $schedules;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Anamnesis", mappedBy="medicalStaff", orphanRemoval=true)
     */
    private $anamneses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Speciality", inversedBy="medicalStaff")
     * @ORM\JoinColumn(nullable=false)
     */
    private $speciality;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Results", mappedBy="medicalStaff", orphanRemoval=true)
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="medicalStaff", orphanRemoval=true)
     */
    private $reservations;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
        $this->anamneses = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getHall(): ?Hall
    {
        return $this->hall;
    }

    public function setHall(?Hall $hall): self
    {
        $this->hall = $hall;

        return $this;
    }

    /**
     * @return Collection|Schedule[]
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->addMedicalStaff($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            $schedule->removeMedicalStaff($this);
        }

        return $this;
    }

    /**
     * @return Collection|Anamnesis[]
     */
    public function getAnamneses(): Collection
    {
        return $this->anamneses;
    }

    public function addAnamnese(Anamnesis $anamnese): self
    {
        if (!$this->anamneses->contains($anamnese)) {
            $this->anamneses[] = $anamnese;
            $anamnese->setMedicalStaff($this);
        }

        return $this;
    }

    public function removeAnamnese(Anamnesis $anamnese): self
    {
        if ($this->anamneses->contains($anamnese)) {
            $this->anamneses->removeElement($anamnese);
            // set the owning side to null (unless already changed)
            if ($anamnese->getMedicalStaff() === $this) {
                $anamnese->setMedicalStaff(null);
            }
        }

        return $this;
    }

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * @return Collection|Results[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Results $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setMedicalStaff($this);
        }

        return $this;
    }

    public function removeResult(Results $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getMedicalStaff() === $this) {
                $result->setMedicalStaff(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setMedicalStaff($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getMedicalStaff() === $this) {
                $reservation->setMedicalStaff(null);
            }
        }

        return $this;
    }
}
