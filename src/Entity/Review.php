<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReviewRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_review"})
     * @Groups({"show_review"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_review"})
     * @Groups({"show_review"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_review"})
     * @Groups({"show_review"})
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_review"})
     * @Groups({"show_review"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_review"})
     * @Groups({"show_review"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_review"})
     * @Groups({"show_review"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Song::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_review"})
     * @Groups({"show_review"})
     */
    private $song;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_review"})
     * @Groups({"show_review"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): self
    {
        $this->song = $song;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
