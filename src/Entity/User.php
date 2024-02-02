<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['basic_infos'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['basic_infos'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['basic_infos'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 55)]
    #[Groups(['basic_infos'])]
    private ?string $name = null;

    #[ORM\Column(length: 55)]
    #[Groups(['basic_infos'])]
    private ?string $surname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['basic_infos'])]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column]
    #[Groups(['basic_infos'])]
    private ?bool $first_connection = null;

    #[ORM\Column(length: 255)]
    #[Groups(['basic_infos'])]
    private ?string $address = null;

    #[ORM\Column(length: 5)]
    #[Groups(['basic_infos'])]
    private ?string $zipcode = null;

    #[ORM\Column(length: 128)]
    #[Groups(['basic_infos'])]
    private ?string $city = null;

    #[ORM\Column(length: 10)]
    #[Groups(['basic_infos'])]
    private ?string $phone = null;

    #[ORM\Column]
    #[Groups(['basic_infos'])]
    private ?int $sport_age = null;

    #[ORM\Column]
    #[Groups(['basic_infos'])]
    private ?int $token_amount = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['basic_infos'])]
    private ?Civility $civility = null;

    #[ORM\ManyToMany(targetEntity: Level::class, inversedBy: 'users')]
    #[Groups(['basic_infos'])]
    private Collection $level;

    #[ORM\ManyToMany(targetEntity: Planning::class, inversedBy: 'users')]
    private Collection $courses;

    #[ORM\Column(length: 8)]
    #[Groups(['basic_infos'])]
    private ?string $license_serial = null;

    public function __construct()
    {
        $this->level = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function isFirstConnection(): ?bool
    {
        return $this->first_connection;
    }

    public function setFirstConnection(bool $first_connection): static
    {
        $this->first_connection = $first_connection;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSportAge(): ?int
    {
        return $this->sport_age;
    }

    public function setSportAge(int $sport_age): static
    {
        $this->sport_age = $sport_age;

        return $this;
    }

    public function getTokenAmount(): ?int
    {
        return $this->token_amount;
    }

    public function setTokenAmount(int $token_amount): static
    {
        $this->token_amount = $token_amount;

        return $this;
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function setCivility(?Civility $civility): static
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * @return Collection<int, Level>
     */
    public function getLevel(): Collection
    {
        return $this->level;
    }

    public function addLevel(Level $level): static
    {
        if (!$this->level->contains($level)) {
            $this->level->add($level);
        }

        return $this;
    }

    public function removeLevel(Level $level): static
    {
        $this->level->removeElement($level);

        return $this;
    }

    /**
     * @return Collection<int, Planning>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Planning $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
        }

        return $this;
    }

    public function removeCourse(Planning $course): static
    {
        $this->courses->removeElement($course);

        return $this;
    }

    public function getLicenseSerial(): ?string
    {
        return $this->license_serial;
    }

    public function setLicenseSerial(string $license_serial): static
    {
        $this->license_serial = $license_serial;

        return $this;
    }
}
