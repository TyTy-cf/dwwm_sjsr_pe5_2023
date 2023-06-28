<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:item', 'user:list', 'userOwnGames:post'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:item', 'user:list', 'user:post', 'userOwnGames:post'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:item', 'user:list', 'user:post'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:item', 'user:list', 'user:post'])]
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

    public function __construct()
    {
        $this->userOwnGames = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getWallet(): ?float
    {
        return $this->wallet;
    }

    public function setWallet(float $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, UserOwnGame>
     */
    public function getUserOwnGames(): Collection
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
}
