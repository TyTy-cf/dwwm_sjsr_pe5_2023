<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => 'games:list'
            ]
        ],
    ],
    itemOperations: ['get'],
)]
#[ApiFilter(
    SearchFilter::class, properties: [
        'name' => 'partial',
        'description' => 'partial'
    ],
)]
#[ApiFilter(
    OrderFilter::class,
    properties: [
        'name',
        'price',
    ],
    arguments: [
        'orderParameterName' => 'order'
    ])
]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['games:list', 'userOwnGames:list', 'user:item', 'userOwnGames:post'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['games:list', 'userOwnGames:list', 'user:item'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups('games:list')]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['games:list', 'user:item'])]
    private ?string $thumbnailCover = null;

    #[ORM\Column(length: 255)]
    #[Groups(['games:list', 'userOwnGames:list', 'user:item', 'userOwnGames:post'])]
    private ?string $slug = null;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getThumbnailCover(): ?string
    {
        return $this->thumbnailCover;
    }

    public function setThumbnailCover(string $thumbnailCover): static
    {
        $this->thumbnailCover = $thumbnailCover;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
