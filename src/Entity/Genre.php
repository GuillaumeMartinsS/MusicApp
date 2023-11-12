<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_genre"})
     * @Groups({"show_genre"})
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_genre"})
     * @Groups({"show_genre"})
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_genre"})
     * @Groups({"show_genre"})
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"list_genre"})
     * @Groups({"show_genre"})
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list_genre"})
     * @Groups({"show_genre"})
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_genre"})
     * @Groups({"show_genre"})
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"list_genre"})
     * @Groups({"show_genre"})
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"list_genre"})
     * @Groups({"show_genre"})
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Song::class, mappedBy="genres")
     * @Groups({"list_genre"})
     * @Groups({"show_genre"})
     * 
     */
    private $songs;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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
            $song->addGenre($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->removeElement($song)) {
            $song->removeGenre($this);
        }

        return $this;
    }
}
