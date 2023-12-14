<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SongRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=SongRepository::class)
 */
class Song
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $status;

    /**
     * @ORM\Column(type="date")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $nbLike;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $nbListened;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="songs")
     */
    private $genres;

    /**
     * @ORM\ManyToMany(targetEntity=Playlist::class, inversedBy="songs")
     */
    private $playlists;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="song", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="songs")
     */
    private $users;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getNbLike(): ?int
    {
        return $this->nbLike;
    }

    public function setNbLike(?int $nbLike): self
    {
        $this->nbLike = $nbLike;

        return $this;
    }

    public function getNbListened(): ?int
    {
        return $this->nbListened;
    }

    public function setNbListened(?int $nbListened): self
    {
        $this->nbListened = $nbListened;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists[] = $playlist;
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        $this->playlists->removeElement($playlist);

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setSong($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getSong() === $this) {
                $review->setSong(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addSong($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeSong($this);
        }

        return $this;
    }
}
