<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournamentRepository")
 */
class Tournament
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="tournament")
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TournamentParticipants", mappedBy="tournament")
     */
    private $tournamentParticipants;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setTournament($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getTournament() === $this) {
                $game->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TournamentParticipants[]
     */
    public function getTournamentParticipants(): Collection
    {
        return $this->tournamentParticipants;
    }

    public function addTournamentParticipant(TournamentParticipants $tournamentParticipant): self
    {
        if (!$this->tournamentParticipants->contains($tournamentParticipant)) {
            $this->tournamentParticipants[] = $tournamentParticipant;
            $tournamentParticipant->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentParticipant(TournamentParticipants $tournamentParticipant): self
    {
        if ($this->tournamentParticipants->contains($tournamentParticipant)) {
            $this->tournamentParticipants->removeElement($tournamentParticipant);
            // set the owning side to null (unless already changed)
            if ($tournamentParticipant->getTournament() === $this) {
                $tournamentParticipant->setTournament(null);
            }
        }

        return $this;
    }
}
