<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HallRepository")
 */
class Hall
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departament", inversedBy="halls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departament;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;



    public function __construct()
    {
        $this->itemsLocations = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function removeItemsLocation(ItemsLocation $itemsLocation): self
    {
        if ($this->itemsLocations->contains($itemsLocation)) {
            $this->itemsLocations->removeElement($itemsLocation);
            // set the owning side to null (unless already changed)
            if ($itemsLocation->getHall() === $this) {
                $itemsLocation->setHall(null);
            }
        }

        return $this;
    }
}
