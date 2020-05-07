<?php

namespace App\Entity\Security;

use App\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Security\PasswordResetTokenRepository")
 */
class PasswordResetToken
{

    public const EXPIRY_IN = 30; // time in minutes

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $token = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $expiry_at = null;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?User $user = null;

    /**
     * PasswordResetToken constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->expiry_at = new \DateTime('+' . self::EXPIRY_IN . ' minutes');
    }

    /**
     * @return bool
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function isExpiry(): bool
    {
        return $this->getExpiryAt() < new \DateTime('now');
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
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getExpiryAt(): ?DateTimeInterface
    {
        return $this->expiry_at;
    }

    /**
     * @param DateTimeInterface $expiry_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setExpiryAt(DateTimeInterface $expiry_at): self
    {
        $this->expiry_at = $expiry_at;

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
     * @param User $user
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
