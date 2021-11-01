<?php

namespace App\Entity;

use App\Repository\SiteElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SiteElementRepository::class)
 */
class SiteElement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="siteElements")
     */
    private $site;
    /**
     * @ORM\Column(type="integer")
     */
    private $numberRoon;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $number;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $consitance;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $position;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=RentalContract::class, inversedBy="siteElements")
     */
    private $rentalContracts;

    public function __construct()
    {
        $this->rentalContracts = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection|RentalContract[]
     */
    public function getRentalContracts(): Collection
    {
        return $this->rentalContracts;
    }

    public function addRentalContract(RentalContract $rentalContract): self
    {
        if (!$this->rentalContracts->contains($rentalContract)) {
            $this->rentalContracts[] = $rentalContract;
        }

        return $this;
    }

    public function removeRentalContract(RentalContract $rentalContract): self
    {
        $this->rentalContracts->removeElement($rentalContract);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumberRoon()
    {
        return $this->numberRoon;
    }

    /**
     * @param mixed $numberRoon
     */
    public function setNumberRoon($numberRoon): void
    {
        $this->numberRoon = $numberRoon;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getConsitance()
    {
        return $this->consitance;
    }

    /**
     * @param mixed $consitance
     */
    public function setConsitance($consitance): void
    {
        $this->consitance = $consitance;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

}
