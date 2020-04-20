<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MedicalItemRepository")
 */
class MedicalItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ItemsLocation", mappedBy="medicalItem", orphanRemoval=true)
     */
    private $itemsLocations;

    public function __construct()
    {
        $this->itemsLocations = new ArrayCollection();
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
     * @return Collection|ItemsLocation[]
     */
    public function getItemsLocations(): Collection
    {
        return $this->itemsLocations;
    }

    public function addItemsLocation(ItemsLocation $itemsLocation): self
    {
        if (!$this->itemsLocations->contains($itemsLocation)) {
            $this->itemsLocations[] = $itemsLocation;
            $itemsLocation->setMedicalItem($this);
        }

        return $this;
    }

    public function removeItemsLocation(ItemsLocation $itemsLocation): self
    {
        if ($this->itemsLocations->contains($itemsLocation)) {
            $this->itemsLocations->removeElement($itemsLocation);
            // set the owning side to null (unless already changed)
            if ($itemsLocation->getMedicalItem() === $this) {
                $itemsLocation->setMedicalItem(null);
            }
        }

        return $this;
    }
}
