<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=True)
     * @Assert\NotBlank(message="Email is required.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",
     *     message="Invalid email format."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Password cannot be blank.")
     * @Assert\Length(min=6, minMessage="Password must be at least {{ limit }} characters long.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idEquipe;

    /**
     * @ORM\Column(type="string", length=2500, nullable=true)
     */
    private $otp;

    /**
     * @ORM\Column(type="string", length=8, nullable=false)
     * @Assert\NotBlank(message="Phone number is required.")
     * @Assert\Regex(
     *     pattern="/^\d{8,}$/",
     *     message="Phone number must be at least 8 digits."
     * )
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=3000, nullable=false)
     * @Assert\NotBlank(message="First name is required.")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "First name cannot be shorter than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+(?:\s+[a-zA-Z]+)*$/",
     *     message="First name must contain only alphabetic characters."
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=3000, nullable=false)
     * @Assert\NotBlank(message="Last name is required.")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Last name cannot be shorter than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+(?:\s+[a-zA-Z]+)*$/",
     *     message="Last name must contain only alphabetic characters."
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=3000, nullable=false)
     * @Assert\NotBlank(message="Username is required.")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Username cannot be shorter than {{ limit }} characters."
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isBanned = false;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->email = $id;

        return $this;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getIdEquipe(): ?int
    {
        return $this->idEquipe;
    }

    public function setIdEquipe(?int $idEquipe): self
    {
        $this->idEquipe = $idEquipe;

        return $this;
    }

    public function getOtp(): ?string
    {
        return $this->otp;
    }

    public function setOtp(?string $otp): self
    {
        $this->otp = $otp;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles()
    {
        return [$this->role];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier()
    {
        return $this->username;
    }

    public function getIsBanned(): bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

        return $this;
    }
}
