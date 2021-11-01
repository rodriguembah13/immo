<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 */
class Site
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon;
    /**
     * @ORM\OneToMany(targetEntity=SiteElement::class, mappedBy="site")
     */
    private $siteElements;

    public function __construct()
    {
        $this->siteElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $siteElement->setSite($this);
        }

        return $this;
    }

    public function removeSiteElement(SiteElement $siteElement): self
    {
        if ($this->siteElements->removeElement($siteElement)) {
            // set the owning side to null (unless already changed)
            if ($siteElement->getSite() === $this) {
                $siteElement->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon): void
    {
        $this->icon = $icon;
    }

}
