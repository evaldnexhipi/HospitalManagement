<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", inversedBy="schedules")
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MedicalStaff", inversedBy="schedules")
     */
    private $medicalStaff;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    public function __construct()
    {
        $this->client = new ArrayCollection();
        $this->medicalStaff = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(Client $client): self
    {
        if (!$this->client->contains($client)) {
            $this->client[] = $client;
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->client->contains($client)) {
            $this->client->removeElement($client);
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
        }

        return $this;
    }

    public function removeMedicalStaff(MedicalStaff $medicalStaff): self
    {
        if ($this->medicalStaff->contains($medicalStaff)) {
            $this->medicalStaff->removeElement($medicalStaff);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
