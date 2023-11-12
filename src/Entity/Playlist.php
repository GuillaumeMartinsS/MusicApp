<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PlaylistRepository::class)
 */
class Playlist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $album;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $nbLike;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Song::class, mappedBy="playlists")
     */
    private $songs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="playlists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_playlist"})
     * @Groups({"show_playlist"})
     */
    private $user;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function isAlbum(): ?bool
    {
        return $this->album;
    }

    public function setAlbum(bool $album): self
    {
        $this->album = $album;

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

    public function getNbLike(): ?int
    {
        return $this->nbLike;
    }

    public function setNbLike(?int $nbLike): self
    {
        $this->nbLike = $nbLike;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
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
     * @return Collection<int, Song>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs[] = $song;
            $song->addPlaylist($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->removeElement($song)) {
            $song->removePlaylist($this);
        }

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
