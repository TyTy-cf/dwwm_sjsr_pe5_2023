<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'denormalization_context' => [
                'groups' => 'user:post'
            ]
        ],
        'get' => [
            'normalization_context' => [
                'groups' => 'user:list'
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => 'user:item'
            ]
        ],
        'put',
    ],
    paginationItemsPerPage: 10
)]
#[ApiFilter(
    SearchFilter::class, properties: [
        'name' => 'partial',
        'email' => 'partial',
        'nickname' => 'partial',
        'createdAt' => 'partial',
        'country.name' => 'partial',
    ],
)]
#[ApiFilter(
    DateFilter::class, properties: [
        'createdAt'
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:item', 'user:list', 'user:post', 'userOwnGames:post', 'review:list', 'review:item', 'review:post'])]
    #[Assert\NotBlank(message: 'Ce nom doit être renseigné')]
    #[Assert\Unique(message: 'Ce nom existe déjà')]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:item', 'user:list', 'user:post'])]
    #[Assert\NotBlank(message: 'Le pseudo doit être renseigné')]
    private ?string $nickname = null;

    #[ORM\Column]
    #[Groups(['user:item', 'user:list'])]
    private ?float $wallet = 0.0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['user:item', 'user:list'])]
    private ?\DateTimeInterface $createdAt;

    #[ORM\ManyToOne]
    #[Groups(['user:item', 'user:list'])]
    private ?Country $country = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserOwnGame::class, orphanRemoval: true)]
    #[Groups('user:item')]
    private Collection $userOwnGames;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->userOwnGames = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->reviews = new ArrayCollection();
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return User
     */
    public function setName(?string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * @param string|null $nickname
     * @return User
     */
    public function setNickname(?string $nickname): User
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getWallet(): ?float
    {
        return $this->wallet;
    }

    /**
     * @param float|null $wallet
     * @return User
     */
    public function setWallet(?float $wallet): User
    {
        $this->wallet = $wallet;
        return $this;
    }

    /**
     * @return \DateTime|\DateTimeInterface|null
     */
    public function getCreatedAt(): \DateTime|\DateTimeInterface|null
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|\DateTimeInterface|null $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTime|\DateTimeInterface|null $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Country|null
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * @param Country|null $country
     * @return User
     */
    public function setCountry(?Country $country): User
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getUserOwnGames(): ArrayCollection|Collection
    {
        return $this->userOwnGames;
    }

    public function addUserOwnGame(UserOwnGame $userOwnGame): static
    {
        if (!$this->userOwnGames->contains($userOwnGame)) {
            $this->userOwnGames->add($userOwnGame);
            $userOwnGame->setUser($this);
        }

        return $this;
    }

    public function removeUserOwnGame(UserOwnGame $userOwnGame): static
    {
        if ($this->userOwnGames->removeElement($userOwnGame)) {
            // set the owning side to null (unless already changed)
            if ($userOwnGame->getUser() === $this) {
                $userOwnGame->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return string return the total duration as HH:MM
     */
    #[Groups(['user:item'])]
    public function getTotalGameTime(): string {
        $totalGameTime = 0;
        foreach ($this->userOwnGames as $userOwnGame) {
            /** @var UserOwnGame $userOwnGame */
            $totalGameTime += $userOwnGame->getGameTime();
        }
        $hours = floor($totalGameTime / 3600);
        $minutes = ($totalGameTime % 60);
        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }
        return $hours. 'h' . $minutes;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getReviews(): ArrayCollection|Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    #[Groups(['user:item', 'user:list'])]
    public function isAdmin(): bool {
        return in_array('ROLE_ADMIN', $this->roles);
    }
}
