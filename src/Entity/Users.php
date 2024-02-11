<?php
namespace App\Entity;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;


#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["getGames", "getUsers"])]
    #[ORM\Column(type: 'integer')]
    #[SerializedName('id_user')] 
    private $id_user;

    #[ORM\Column(length: 255)]
    #[Groups(["getGames", "getUsers"])]
    private ?string $player_name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getGames", "getUsers"])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getGames", "getUsers"])]
    private ?string $password = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(["getGames", "getUsers"])]
    public $last_game_id;

    // #[ORM\OneToMany(mappedBy: 'users', targetEntity: Game::class)]
    // #[Groups(['getUsers'])]
    // private $game;

    // public function __construct()
    // {
    //     $this->game = new ArrayCollection();
    // }

   
    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function getPlayerName(): ?string
    {
        return $this->player_name;
    }

    public function setPlayerName(string $player_name): static
    {
        $this->player_name = $player_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    
    #[Groups(["getGames", "getUsers"])]
    #[SerializedName('last_game_id')]
    public function getLastGame(): ?int
    {
    
        return $this->last_game_id;
    }
    #[Groups(["getGames", "getUsers"])]
    #[SerializedName('last_game_id')]
    public function setLastGame(int $last_game_id): static
    {
        $this->last_game_id = $last_game_id;

        return $this;
    }

    // /**
    //  * @return Collection<int, Game>
    //  */
    // public function getGame(): Collection
    // {
    //     return $this->game;
    // }

    // public function addGame(Game $gam): self
    // {
    //     if (!$this->game->contains($gam)) {
    //         $this->game[] = $gam;
    //         $gam->setUser($this);
    //     }

    //     return $this;
    // }
}
