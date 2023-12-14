<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @MaxDepth(1)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $certification;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"list_song"})
     * @Groups({"show_song"})
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="user")
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=Playlist::class, mappedBy="user", orphanRemoval=true)
     */
    private $playlists;

    /**
     * @ORM\ManyToMany(targetEntity=Song::class, inversedBy="users")
     */
    private $songs;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="subcribers")
     * @MaxDepth(1)
     */
    private $subscriptions;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="subscriptions")
     * @Groups({"list_user"})
     * @Groups({"show_user"})
     * @MaxDepth(1)
     */
    private $subcribers;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->songs = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->subcribers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
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
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function isCertification(): ?bool
    {
        return $this->certification;
    }

    public function setCertification(bool $certification): self
    {
        $this->certification = $certification;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

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
            $playlist->setUser($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getUser() === $this) {
                $playlist->setUser(null);
            }
        }

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
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        $this->songs->removeElement($song);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(self $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
        }

        return $this;
    }

    public function removeSubscription(self $subscription): self
    {
        $this->subscriptions->removeElement($subscription);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubcribers(): Collection
    {
        return $this->subcribers;
    }

    public function addSubcriber(self $subcriber): self
    {
        if (!$this->subcribers->contains($subcriber)) {
            $this->subcribers[] = $subcriber;
            $subcriber->addSubscription($this);
        }

        return $this;
    }

    public function removeSubcriber(self $subcriber): self
    {
        if ($this->subcribers->removeElement($subcriber)) {
            $subcriber->removeSubscription($this);
        }

        return $this;
    }
}
