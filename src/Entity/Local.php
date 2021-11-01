<?php

namespace App\Entity;

use App\Repository\LocalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocalRepository::class)
 */
class Local
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\ManyToMany(targetEntity=RentalContract::class, mappedBy="locals")
     */
    private $rentalContracts;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Depense::class, mappedBy="local")
     */
    private $depenses;
    public function __construct()
    {
        $this->rentalContracts = new ArrayCollection();
        $this->updatedAt=new \DateTime('now');
        $this->createdAt=new \DateTime('now');
        $this->depenses = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberRoon(): ?int
    {
        return $this->numberRoon;
    }

    public function setNumberRoon(int $numberRoon): self
    {
        $this->numberRoon = $numberRoon;

        return $this;
    }
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
            $rentalContract->addLocal($this);
        }

        return $this;
    }

    public function removeRentalContract(RentalContract $rentalContract): self
    {
        if ($this->rentalContracts->removeElement($rentalContract)) {
            $rentalContract->removeLocal($this);
        }

        return $this;
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
    public function getConsitance()
    {
        return $this->consitance;
    }

    /**
     * @param mixed $consitance
     * @return Local
     */
    public function setConsitance($consitance)
    {
        $this->consitance = $consitance;
        return $this;
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
     * @return Local
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function __toString()
    {
       return $this->consitance.'_'.$this->number;
    }
    public function getName()
    {
        $name="";

        return $this->number;
    }

    /**
     * @return Collection|Depense[]
     */
    public function getDepenses(): Collection
    {
        return $this->depenses;
    }

    public function addDepense(Depense $depense): self
    {
        if (!$this->depenses->contains($depense)) {
            $this->depenses[] = $depense;
            $depense->setLocal($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): self
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getLocal() === $this) {
                $depense->setLocal(null);
            }
        }

        return $this;
    }
}
