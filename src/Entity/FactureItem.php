<?php

namespace App\Entity;

use App\Repository\FactureItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureItemRepository::class)
 */
class FactureItem
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
    private $amount;

    /**
     * @ORM\Column(type="float")
     */
    private $amountDue;

    /**
     * @ORM\ManyToOne(targetEntity=Rental::class, inversedBy="factureItems")
     */
    private $rental;

    /**
     * @ORM\ManyToOne(targetEntity=Facture::class, inversedBy="factureItems")
     */
    private $facture;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRental(): ?Rental
    {
        return $this->rental;
    }

    public function setRental(?Rental $rental): self
    {
        $this->rental = $rental;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        return $this;
    }
}
