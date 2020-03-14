<?php

namespace App\Entity\Security;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Security\LoginRepository")
 */
class Login
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $device = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $ip = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $location = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $logged_at = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="logins")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * Login constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->logged_at = new DateTime();
    }

    /**
     * @return int|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getDevice(): ?string
    {
        return $this->device;
    }

    /**
     * @param string $device
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setDevice(string $device): self
    {
        $this->device = $device;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getLoggedAt(): ?\DateTimeInterface
    {
        return $this->logged_at;
    }

    /**
     * @param \DateTimeInterface $logged_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setLoggedAt(\DateTimeInterface $logged_at): self
    {
        $this->logged_at = $logged_at;

        return $this;
    }

    /**
     * @return User|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }
}
