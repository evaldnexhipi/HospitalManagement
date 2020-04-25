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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ItemsLocation", mappedBy="hall")
     */
    private $itemsLocations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MedicalStaff", mappedBy="hall", orphanRemoval=true)
     */
    private $medicalStaff;



    public function __construct()
    {
        $this->itemsLocations = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->medicalStaff = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setHall($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getHall() === $this) {
                $user->setHall(null);
            }
        }

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
            $itemsLocation->addHall($this);
        }

        return $this;
    }

    /**
     * @return Collection|MedicalStaff[]
     */
    public function getMedicalStaff(): Collection
    {
        return $this->medicalStaff;
    }

    public function addMedicalStaff(MedicalStaff $medicalStaff): self
    {
        if (!$this->medicalStaff->contains($medicalStaff)) {
            $this->medicalStaff[] = $medicalStaff;
            $medicalStaff->setHall($this);
        }

        return $this;
    }

    public function removeMedicalStaff(MedicalStaff $medicalStaff): self
    {
        if ($this->medicalStaff->contains($medicalStaff)) {
            $this->medicalStaff->removeElement($medicalStaff);
            // set the owning side to null (unless already changed)
            if ($medicalStaff->getHall() === $this) {
                $medicalStaff->setHall(null);
            }
        }

        return $this;
    }
}
