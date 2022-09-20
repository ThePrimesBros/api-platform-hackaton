<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\HackatonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Blameable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['hackaton:readOne']], // Normalization par défaut
    denormalizationContext: ['groups' => ['hackaton:write']], // Denormalization par défaut
)]
#[Get(
    description: 'Get a hackaton',
)]
#[GetCollection(
    description: 'Get all hackatons',
    normalizationContext: ['groups' => ['hackaton:readAll']],
    security: 'is_granted("ROLE_COACH")',
)]
#[Post(
    description: 'Create a hackaton',
    security: 'is_granted("ROLE_COACH")',
)]
#[Patch(
    description: 'Update a hackaton',
    security: 'is_granted("ROLE_COACH")',
)]
#[Delete(
    description: 'Delete a hackaton',
    security: 'is_granted("ROLE_ADMIN" or object.owner == user)',
)]
#[ORM\Entity(repositoryClass: HackatonRepository::class)]
class Hackaton
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[Groups(['hackaton:readOne', 'hackaton:readAll', 'hackaton:write'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['hackaton:readOne', 'hackaton:readAll', 'hackaton:write'])]
    #[ORM\Column(length: 255)]
    private ?string $customer = null;

    #[Groups(['hackaton:readOne', 'hackaton:readAll', 'hackaton:write'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[Groups(['hackaton:readOne', 'hackaton:readAll', 'hackaton:write'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[Groups(['hackaton:readOne'])]
    #[ORM\OneToMany(mappedBy: 'hackaton', targetEntity: Document::class, orphanRemoval: true)]
    private Collection $documents;

    #[Groups(['hackaton:readOne'])]
    #[ORM\OneToMany(mappedBy: 'hackaton', targetEntity: Groupe::class, orphanRemoval: true)]
    private Collection $groupes;

    #[Groups(['hackaton:readOne'])]
    #[ORM\OneToMany(mappedBy: 'hackaton', targetEntity: Event::class, orphanRemoval: true)]
    private Collection $events;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'hackatons')]
    private Collection $participants;

    #[Blameable(on: 'create')]
    #[ORM\ManyToOne(inversedBy: 'hackatonsCreated')]
    private ?User $owner = null;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->participants = new ArrayCollection();
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

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function setCustomer(string $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setHackaton($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getHackaton() === $this) {
                $document->setHackaton(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setHackaton($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getHackaton() === $this) {
                $groupe->setHackaton(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setHackaton($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getHackaton() === $this) {
                $event->setHackaton(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
