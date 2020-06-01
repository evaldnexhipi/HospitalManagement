<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TreatmentRepository")
 */
class Treatment
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Anamnesis", mappedBy="treatment")
     */
    private $anamneses;

    public function __construct()
    {
        $this->anamneses = new ArrayCollection();
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
            $anamnese->setTreatment($this);
        }

        return $this;
    }

    public function removeAnamnese(Anamnesis $anamnese): self
    {
        if ($this->anamneses->contains($anamnese)) {
            $this->anamneses->removeElement($anamnese);
            // set the owning side to null (unless already changed)
            if ($anamnese->getTreatment() === $this) {
                $anamnese->setTreatment(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
