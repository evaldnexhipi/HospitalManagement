<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecialityRepository")
 */
class Speciality
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MedicalStaff", mappedBy="speciality", orphanRemoval=true)
     */
    private $medicalStaff;

    public function __construct()
    {
        $this->medicalStaff = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
            $medicalStaff->setSpeciality($this);
        }

        return $this;
    }

    public function removeMedicalStaff(MedicalStaff $medicalStaff): self
    {
        if ($this->medicalStaff->contains($medicalStaff)) {
            $this->medicalStaff->removeElement($medicalStaff);
            // set the owning side to null (unless already changed)
            if ($medicalStaff->getSpeciality() === $this) {
                $medicalStaff->setSpeciality(null);
            }
        }

        return $this;
    }
}
