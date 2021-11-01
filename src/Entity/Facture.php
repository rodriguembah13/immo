<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="float")
     */
    private $amountDue;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Tenant::class, inversedBy="factures")
     */
    private $tenant;

/*
     * @ORM\ManyToOne(targetEntity=Rental::class, inversedBy="factures")

    private $rental;
*/
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="factures")
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity=FactureItem::class, mappedBy="facture")
     */
    private $factureItems;

    public function __construct()
    {
        $this->factureItems = new ArrayCollection();
        $this->updatedAt=new \DateTime('now');
        $this->createdAt=new \DateTime('now');
        //$this->amount=0.0;
        $this->amountDue=0.0;
        $this->total=0.0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountDue(): ?float
    {
        return $this->amountDue;
    }

    public function setAmountDue(float $amountDue): self
    {
        $this->amountDue = $amountDue;

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

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(?Tenant $tenant): self
    {
        $this->tenant = $tenant;

        return $this;
    }

   /* public function getRental(): ?Rental
    {
        return $this->rental;
    }

    public function setRental(?Rental $rental): self
    {
        $this->rental = $rental;

        return $this;
    }*/

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|FactureItem[]
     */
    public function getFactureItems(): Collection
    {
        return $this->factureItems;
    }

    public function addFactureItem(FactureItem $factureItem): self
    {
        if (!$this->factureItems->contains($factureItem)) {
            $this->factureItems[] = $factureItem;
            $factureItem->setFacture($this);
        }

        return $this;
    }

    public function removeFactureItem(FactureItem $factureItem): self
    {
        if ($this->factureItems->removeElement($factureItem)) {
            // set the owning side to null (unless already changed)
            if ($factureItem->getFacture() === $this) {
                $factureItem->setFacture(null);
            }
        }

        return $this;
    }
}
