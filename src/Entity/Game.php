<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Users; 
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;


#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["getGames", "getUsers"])]
    #[SerializedName('id_game')]
    private $id_game;

    #[ORM\Column(type: 'integer')]
    #[Groups(['getGames'])]
    private $score;

    #[ORM\Column(type: 'integer')]
    #[Groups(['getGames'])]
    private $level;

    #[ORM\Column(type: 'integer')]
    #[Groups(['getGames'])]
    private $duration;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getGames'])]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getGames'])]
    private ?\DateTimeInterface $date_end = null;

    // #[ORM\Column(type: 'integer')] 
    #[Groups(['getGames'])]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id_user", onDelete: "CASCADE")]
    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'game')]
    private ?Users $user = null;


    public function getIdGame(): ?int
    {
        return $this->id_game;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): static
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

}
