<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("name")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $uid;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $name = null;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private ?string $password = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?array $roles = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=255,
     *     nullable=true,
     *     options={"default": "/images/system/default_avatar.jpg"}
     * )
     */
    private ?string $avatar_url = null;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private ?string $account_confirmation_token = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $account_confirmed_at = null;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private ?string $password_reset_token = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $password_reset_at = null;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $is_private = false;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private bool $is_active = true;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $is_certified = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_at = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updated_at = null;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->uid = uniqid("sc_");
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    /**
     * @param string $password
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    /**
     * @param string|null $avatar_url
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setAvatarUrl(?string $avatar_url): self
    {
        $this->avatar_url = $avatar_url;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getAccountConfirmationToken(): ?string
    {
        return $this->account_confirmation_token;
    }

    /**
     * @param string|null $confirmation_token
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setAccountConfirmationToken(?string $confirmation_token): self
    {
        $this->account_confirmation_token = $confirmation_token;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getAccountConfirmedAt(): ?DateTimeInterface
    {
        return $this->account_confirmed_at;
    }

    /**
     * @param DateTimeInterface|null $account_confirmed_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setAccountConfirmedAt(?DateTimeInterface $account_confirmed_at): self
    {
        $this->account_confirmed_at = $account_confirmed_at;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getPasswordResetToken(): ?string
    {
        return $this->password_reset_token;
    }

    /**
     * @param string|null $password_reset_token
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setPasswordResetToken(?string $password_reset_token): self
    {
        $this->password_reset_token = $password_reset_token;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getPasswordResetAt(): ?DateTimeInterface
    {
        return $this->password_reset_at;
    }

    /**
     * @param DateTimeInterface|null $password_reset_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setPasswordResetAt(?DateTimeInterface $password_reset_at): self
    {
        $this->password_reset_at = $password_reset_at;

        return $this;
    }

    /**
     * @return bool|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getIsPrivate(): ?bool
    {
        return $this->is_private;
    }

    /**
     * @param bool $is_private
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setIsPrivate(bool $is_private): self
    {
        $this->is_private = $is_private;

        return $this;
    }

    /**
     * @return bool|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    /**
     * @param bool $is_active
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeInterface $created_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @param DateTimeInterface|null $updated_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setUpdatedAt(?DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return bool|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getIsCertified(): ?bool
    {
        return $this->is_certified;
    }

    /**
     * @param bool $is_certified
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setIsCertified(bool $is_certified): self
    {
        $this->is_certified = $is_certified;

        return $this;
    }
}