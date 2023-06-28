<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserOwnGameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserOwnGameRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => 'userOwnGames:list'
            ]
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => 'userOwnGames:post'
            ]
        ]
    ],
    itemOperations: ['get'],
)]
#[ApiFilter(
    BooleanFilter::class, properties: [
        'isInstalled'
    ]
)]
#[ApiFilter(
    DateFilter::class, properties: [
        'createdAt'
    ]
)]
class UserOwnGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('userOwnGames:list')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['userOwnGames:list', 'user:item', 'userOwnGames:post'])]
    private ?Game $game = null;

    #[ORM\ManyToOne(inversedBy: 'userOwnGames')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['userOwnGames:list', 'userOwnGames:post'])]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['userOwnGames:list', 'user:item'])]
    private ?bool $isInstalled = false;

    #[ORM\Column]
    #[Groups(['userOwnGames:list', 'user:item'])]
    private ?int $gameTime = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['userOwnGames:list', 'user:item'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['userOwnGames:list', 'user:item'])]
    private ?\DateTimeInterface $lastUsedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isInstalled(): ?bool
    {
        return $this->isInstalled;
    }

    public function setIsInstalled(bool $isInstalled): static
    {
        $this->isInstalled = $isInstalled;

        return $this;
    }

    public function getGameTime(): ?int
    {
        return $this->gameTime;
    }

    public function setGameTime(int $gameTime): static
    {
        $this->gameTime = $gameTime;

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

    public function getLastUsedAt(): ?\DateTimeInterface
    {
        return $this->lastUsedAt;
    }

    public function setLastUsedAt(\DateTimeInterface $lastUsedAt): static
    {
        $this->lastUsedAt = $lastUsedAt;

        return $this;
    }

    /**
     * Converti l'attribut gameTime (qui est en seconde)
     *
     * @return string
     */
    #[Groups(['userOwnGames:list', 'user:item'])]
    public function getDurationHM(): string {
        $hours = floor($this->gameTime / 3600);
        $minutes = ($this->gameTime % 60);
        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }
        return $hours. 'h' . $minutes;
    }
}
