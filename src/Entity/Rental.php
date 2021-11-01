<?php

namespace App\Entity;

use App\Repository\RentalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RentalRepository::class)
 */
class Rental
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $beginDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rentals")
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=Tenant::class, inversedBy="rentals")
     */
    private $tenant;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeRental;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $day;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $month;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $year;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amountDue;

    /**
     * @ORM\OneToMany(targetEntity=FactureItem::class, mappedBy="rental")
     */
    private $factureItems;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;
    /**
     * Rental constructor.
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->createdAt = new \DateTime('now');
        $this->factureItems = new ArrayCollection();
        $this->active=true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(?\DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
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

    public function getAmountDue(): ?float
    {
        return $this->amountDue;
    }

    public function setAmountDue(float $amountDue): self
    {
        $this->amountDue = $amountDue;

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

    /**
     * @return mixed
     */
    public function getTypeRental()
    {
        return $this->typeRental;
    }

    /**
     * @param mixed $typeRental
     */
    public function setTypeRental($typeRental): void
    {
        $this->typeRental = $typeRental;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day): void
    {
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month): void
    {
        $this->month = $month;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }

    public function __toString()
    {
        return $this->tenant . ' ' . $this->month . '-' . $this->year;
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
            $factureItem->setRental($this);
        }

        return $this;
    }

    public function removeFactureItem(FactureItem $factureItem): self
    {
        if ($this->factureItems->removeElement($factureItem)) {
            // set the owning side to null (unless already changed)
            if ($factureItem->getRental() === $this) {
                $factureItem->setRental(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Rental
     */
    public function setActive(bool $active): Rental
    {
        $this->active = $active;
        return $this;
    }

    public function getMonthInt($val)
    {
        $re = 0;
        if ($val == 'Jaunary') {
            $re = 1;
        } elseif ($val == 'Febraury') {
            $re = 2;
        } elseif ($val == 'March') {
            $re = 3;
        } elseif ($val == 'April') {
            $re = 4;
        } elseif ($val == 'May') {
            $re = 5;
        } elseif ($val == 'June') {
            $re = 6;
        } elseif ($val == 'July') {
            $re = 7;
        } elseif ($val == 'August') {
            $re = 8;
        } elseif ($val == 'September') {
            $re = 9;
        } elseif ($val == 'October') {
            $re = 10;
        } elseif ($val == 'November') {
            $re = 11;
        } elseif ($val == 'December') {
            $re = 12;
        }
        return $re;
    }
}
