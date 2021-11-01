<?php

namespace App\Entity;

use App\Repository\RentalContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RentalContractRepository::class)
 */
class RentalContract
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
     * @ORM\ManyToOne(targetEntity=Tenant::class, inversedBy="rentalContracts")
     */
    private $tenant;

    /**
     * @ORM\ManyToMany(targetEntity=Local::class, inversedBy="rentalContracts")
     */
    private $locals;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeRental;
    /**
     * @ORM\Column(type="boolean")
     */
    private $status;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amountGaranty;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datedepotGaranty;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amountPrevision;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $informationComplementaires;

    /**
     * @ORM\ManyToMany(targetEntity=SiteElement::class, mappedBy="rentalContracts")
     */
    private $siteElements;
    public function __construct()
    {
        $this->locals = new ArrayCollection();
        $this->updatedAt=new \DateTime('now');
        $this->createdAt=new \DateTime('now');
        $this->status=false;
        $this->siteElements = new ArrayCollection();
    }

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

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(?Tenant $tenant): self
    {
        $this->tenant = $tenant;

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
     * @return Collection|Local[]
     */
    public function getLocals(): Collection
    {
        return $this->locals;
    }

    public function addLocal(Local $local): self
    {
        if (!$this->locals->contains($local)) {
            $this->locals[] = $local;
        }

        return $this;
    }

    public function removeLocal(Local $local): self
    {
        $this->locals->removeElement($local);

        return $this;
    }

    public function getTypeRental(): ?string
    {
        return $this->typeRental;
    }

    public function setTypeRental(?string $typeRental): self
    {
        $this->typeRental = $typeRental;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return RentalContract
     */
    public function setStatus(bool $status): RentalContract
    {
        $this->status = $status;
        return $this;
    }

    public function getAmountGaranty(): ?float
    {
        return $this->amountGaranty;
    }

    public function setAmountGaranty(?float $amountGaranty): self
    {
        $this->amountGaranty = $amountGaranty;

        return $this;
    }

    public function getDatedepotGaranty(): ?\DateTimeInterface
    {
        return $this->datedepotGaranty;
    }

    public function setDatedepotGaranty(?\DateTimeInterface $datedepotGaranty): self
    {
        $this->datedepotGaranty = $datedepotGaranty;

        return $this;
    }

    public function getAmountPrevision(): ?float
    {
        return $this->amountPrevision;
    }

    public function setAmountPrevision(?float $amountPrevision): self
    {
        $this->amountPrevision = $amountPrevision;

        return $this;
    }

    public function getInformationComplementaires(): ?string
    {
        return $this->informationComplementaires;
    }

    public function setInformationComplementaires(?string $informationComplementaires): self
    {
        $this->informationComplementaires = $informationComplementaires;

        return $this;
    }

    /**
     * @return Collection|SiteElement[]
     */
    public function getSiteElements(): Collection
    {
        return $this->siteElements;
    }

    public function addSiteElement(SiteElement $siteElement): self
    {
        if (!$this->siteElements->contains($siteElement)) {
            $this->siteElements[] = $siteElement;
            $siteElement->addRentalContract($this);
        }

        return $this;
    }

    public function removeSiteElement(SiteElement $siteElement): self
    {
        if ($this->siteElements->removeElement($siteElement)) {
            $siteElement->removeRentalContract($this);
        }

        return $this;
    }

}
