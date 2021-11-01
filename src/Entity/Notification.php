<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
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
    private $sender;
    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $notified_id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $receiver;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    /**
     * @return mixed
     */
    public function getNotifiedId()
    {
        return $this->notified_id;
    }

    /**
     * @param mixed $notified_id
     * @return Notification
     */
    public function setNotifiedId($notified_id)
    {
        $this->notified_id = $notified_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

}
