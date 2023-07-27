<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'denormalization_context' => [
                'groups' => 'review:post'
            ]
        ],
        'get' => [
            'normalization_context' => [
                'groups' => 'review:list'
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => 'review:item'
            ]
        ],
    ]
)]
#[ApiFilter(
    SearchFilter::class, properties: [
        'game.slug' => 'exact'
    ]
)]
#[ApiFilter(
    OrderFilter::class, properties: [
        'createdAt',
        'rating',
    ]
)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['review:item'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['review:item', 'review:post'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[Groups(['review:list', 'review:item', 'review:post'])]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['review:list', 'review:item'])]
    private ?\DateTimeInterface $createdAt;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[Groups(['review:list', 'review:item', 'review:post'])]
    private ?Game $game = null;

    #[ORM\Column]
    #[Groups(['review:item'])]
    private ?int $upVote = 0;

    #[ORM\Column]
    #[Groups(['review:item'])]
    private ?int $downVote = 0;

    #[ORM\Column]
    #[Groups(['review:list', 'review:item', 'review:post'])]
    private ?float $rating = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
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

    public function getUpVote(): ?int
    {
        return $this->upVote;
    }

    public function setUpVote(int $upVote): static
    {
        $this->upVote = $upVote;

        return $this;
    }

    public function getDownVote(): ?int
    {
        return $this->downVote;
    }

    public function setDownVote(int $downVote): static
    {
        $this->downVote = $downVote;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }
}
