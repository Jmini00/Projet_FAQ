<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModification = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'votes')]
    private Collection $Voters;

    public function __construct()
    {
        $this->Voters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): static
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getVoters(): Collection
    {
        return $this->Voters;
    }

    public function addVoter(User $voter): static
    {
        if (!$this->Voters->contains($voter)) {
            $this->Voters->add($voter);
            $voter->addVote($this);
        }

        return $this;
    }

    public function removeVoter(User $voter): static
    {
        if ($this->Voters->removeElement($voter)) {
            $voter->removeVote($this);
        }

        return $this;
    }

}
