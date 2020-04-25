<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class Service
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departament", inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departament;

    /**
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="service", orphanRemoval=true)
     */
    private $reservations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MedicalStaff", mappedBy="services")
     */
    private $medicalStaffs;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->medicalStaffs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDepartament(): ?Departament
    {
        return $this->departament;
    }

    public function setDepartament(?Departament $departament): self
    {
        $this->departament = $departament;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

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
            $reservation->setService($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getService() === $this) {
                $reservation->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MedicalStaff[]
     */
    public function getMedicalStaffs(): Collection
    {
        return $this->medicalStaffs;
    }

    public function addMedicalStaff(MedicalStaff $medicalStaff): self
    {
        if (!$this->medicalStaffs->contains($medicalStaff)) {
            $this->medicalStaffs[] = $medicalStaff;
            $medicalStaff->addService($this);
        }

        return $this;
    }

    public function removeMedicalStaff(MedicalStaff $medicalStaff): self
    {
        if ($this->medicalStaffs->contains($medicalStaff)) {
            $this->medicalStaffs->removeElement($medicalStaff);
            $medicalStaff->removeService($this);
        }

        return $this;
    }
}
