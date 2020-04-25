<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnamnesisRepository")
 */
class Anamnesis
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="anamneses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MedicalStaff", inversedBy="anamneses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medicalStaff;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $diagnosis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $symptoms;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Treatment", inversedBy="anamneses")
     */
    private $treatment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getMedicalStaff(): ?MedicalStaff
    {
        return $this->medicalStaff;
    }

    public function setMedicalStaff(?MedicalStaff $medicalStaff): self
    {
        $this->medicalStaff = $medicalStaff;

        return $this;
    }

    public function getDiagnosis(): ?string
    {
        return $this->diagnosis;
    }

    public function setDiagnosis(string $diagnosis): self
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    public function getSymptoms(): ?string
    {
        return $this->symptoms;
    }

    public function setSymptoms(?string $symptoms): self
    {
        $this->symptoms = $symptoms;

        return $this;
    }

    public function getTreatment(): ?Treatment
    {
        return $this->treatment;
    }

    public function setTreatment(?Treatment $treatment): self
    {
        $this->treatment = $treatment;

        return $this;
    }
}
