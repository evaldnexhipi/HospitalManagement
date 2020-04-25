<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemsLocationRepository")
 */
class ItemsLocation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MedicalItem", inversedBy="itemsLocations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medicalItem;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Hall", inversedBy="itemsLocations")
     */
    private $hall;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Room", inversedBy="itemsLocations")
     */
    private $room;

    public function __construct()
    {
        $this->hall = new ArrayCollection();
        $this->room = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedicalItem(): ?MedicalItem
    {
        return $this->medicalItem;
    }

    public function setMedicalItem(?MedicalItem $medicalItem): self
    {
        $this->medicalItem = $medicalItem;

        return $this;
    }

    /**
     * @return Collection|Hall[]
     */
    public function getHall(): Collection
    {
        return $this->hall;
    }

    public function addHall(Hall $hall): self
    {
        if (!$this->hall->contains($hall)) {
            $this->hall[] = $hall;
        }

        return $this;
    }

    public function removeHall(Hall $hall): self
    {
        if ($this->hall->contains($hall)) {
            $this->hall->removeElement($hall);
        }

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRoom(): Collection
    {
        return $this->room;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->room->contains($room)) {
            $this->room[] = $room;
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->room->contains($room)) {
            $this->room->removeElement($room);
        }

        return $this;
    }
}
