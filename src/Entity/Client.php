<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="client", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Schedule", mappedBy="client")
     */
    private $schedules;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Anamnesis", mappedBy="client", orphanRemoval=true)
     */
    private $anamneses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Results", mappedBy="client", orphanRemoval=true)
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="client", orphanRemoval=true)
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
            $schedule->addClient($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            $schedule->removeClient($this);
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
            $anamnese->setClient($this);
        }

        return $this;
    }

    public function removeAnamnese(Anamnesis $anamnese): self
    {
        if ($this->anamneses->contains($anamnese)) {
            $this->anamneses->removeElement($anamnese);
            // set the owning side to null (unless already changed)
            if ($anamnese->getClient() === $this) {
                $anamnese->setClient(null);
            }
        }

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
            $result->setClient($this);
        }

        return $this;
    }

    public function removeResult(Results $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getClient() === $this) {
                $result->setClient(null);
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
            $reservation->setClient($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getClient() === $this) {
                $reservation->setClient(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getUser()->getFirstName();
    }
}
