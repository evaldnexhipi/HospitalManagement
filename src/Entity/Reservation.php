<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use  Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @UniqueEntity(fields = {"availableTimes"}, errorPath="availableTimes", message="This port is already in use on that host")
 */
class Reservation
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MedicalStaff", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medicalStaff;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Choice(choices={"paguaj","pritje"})
     */
    private $status='pritje';

    /**
     * @ORM\Column(type="date")
     */
    private $day;
    /**
     * @ORM\Column(type="string", unique = true)
     */
    private $availableTimes;

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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

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

    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): self
    {
        $this->day = $day;

        return $this;
    }
    public function setAvailableTimes(string $availableTimes): ?self
    {
        $this->availableTimes = $availableTimes;

        return $this;
    }
    public function getAvailableTimes(): ?string
    {
        return $this->availableTimes;
    }


}
