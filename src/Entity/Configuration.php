<?php

namespace App\Entity;

use App\Repository\ConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigurationRepository::class)
 */
class Configuration
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteweb;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bp;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $directeur;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mode;

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Configuration
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @return mixed
     */
    public function getBp()
    {
        return $this->bp;
    }

    /**
     * @param mixed $bp
     * @return Configuration
     */
    public function setBp($bp)
    {
        $this->bp = $bp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     * @return Configuration
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirecteur()
    {
        return $this->directeur;
    }

    /**
     * @param mixed $directeur
     * @return Configuration
     */
    public function setDirecteur($directeur)
    {
        $this->directeur = $directeur;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSiteweb(): ?string
    {
        return $this->siteweb;
    }

    public function setSiteweb(?string $siteweb): self
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
