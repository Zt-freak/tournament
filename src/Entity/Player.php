<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
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
     * @ORM\OneToMany(targetEntity="App\Entity\Participants", mappedBy="player")
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TournamentParticipants", mappedBy="tournament")
     */
    private $tournamentParticipants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="winner")
     */
    private $games;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->tournamentParticipants = new ArrayCollection();
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
     * @return Collection|Participants[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participants $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setPlayer($this);
        }

        return $this;
    }

    public function removeParticipant(Participants $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getPlayer() === $this) {
                $participant->setPlayer(null);
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
            $game->setWinner($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getWinner() === $this) {
                $game->setWinner(null);
            }
        }

        return $this;
    }
}
