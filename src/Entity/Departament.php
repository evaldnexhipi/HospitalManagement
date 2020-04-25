<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups as Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartamentRepository")
 */
class Departament
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Room", mappedBy="departament", orphanRemoval=true)
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hall", mappedBy="departament", orphanRemoval=true)
     */
    private $halls;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Service", mappedBy="departament", orphanRemoval=true)
     */
    private $services;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->halls = new ArrayCollection();
        $this->services = new ArrayCollection();
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

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->setDepartament($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
            // set the owning side to null (unless already changed)
            if ($room->getDepartament() === $this) {
                $room->setDepartament(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Hall[]
     */
    public function getHalls(): Collection
    {
        return $this->halls;
    }

    public function addHall(Hall $hall): self
    {
        if (!$this->halls->contains($hall)) {
            $this->halls[] = $hall;
            $hall->setDepartament($this);
        }

        return $this;
    }

    public function removeHall(Hall $hall): self
    {
        if ($this->halls->contains($hall)) {
            $this->halls->removeElement($hall);
            // set the owning side to null (unless already changed)
            if ($hall->getDepartament() === $this) {
                $hall->setDepartament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setDepartament($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            // set the owning side to null (unless already changed)
            if ($service->getDepartament() === $this) {
                $service->setDepartament(null);
            }
        }

        return $this;
    }
}
