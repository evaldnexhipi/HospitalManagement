<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResultsRepository")
 */
class Results
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MedicalStaff", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medicalStaff;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $analysisPDF;

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

    public function getAnalysisPDF(): ?string
    {
        return $this->analysisPDF;
    }

    public function setAnalysisPDF(string $analysisPDF): self
    {
        $this->analysisPDF = $analysisPDF;

        return $this;
    }
}
