<?php

namespace App\Entity;

use App\Validator\RoomFull;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departament", inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departament;

    /**
     * @ORM\Column(type="string", length=50)
     * @RoomFull()
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Patient", mappedBy="room", orphanRemoval=true)
     */
    private $patients;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfPatients;


    public function __construct()
    {
        $this->patients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

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


    /**
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setRoom($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->contains($patient)) {
            $this->patients->removeElement($patient);
            // set the owning side to null (unless already changed)
            if ($patient->getRoom() === $this) {
                $patient->setRoom(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName()." ".$this->getDepartament()->getName();
    }

    public function getNumberOfPatients(): ?int
    {
        return $this->numberOfPatients;
    }

    public function setNumberOfPatients(int $numberOfPatients): self
    {
        $this->numberOfPatients = $numberOfPatients;

        return $this;
    }
}
