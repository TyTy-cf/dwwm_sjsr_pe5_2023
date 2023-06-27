<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post'
    ],
    itemOperations: [
        'get',
        'put',
    ],
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $nickname = null;

    #[ORM\Column]
    private ?float $wallet = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne]
    private ?Country $country = null;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: UserOwnGame::class, orphanRemoval: true)]
    private Collection $userOwnGames;

    public function __construct()
    {
        $this->userOwnGames = new ArrayCollection();
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
}
