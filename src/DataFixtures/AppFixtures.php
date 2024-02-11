<?php

namespace App\DataFixtures;

use App\Entity\Users;
use App\Entity\Game;
// use App\Controller\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
            // Utilisateur et jeu existants
            $userToto = new Users();
            $userToto->setPlayerName('Toto');
            $userToto->setEmail('toto@gmail.fr');
            $userToto->getLastGame('1');
            $userToto->setPassword('12345');
            $manager->persist($userToto);

            $listUser[] = $userToto;

            // Création d'un jeu de test
            $gameToto = new Game();
            $gameToto->setScore(100);
            $gameToto->setLevel(1);
            $gameToto->setDuration(3600);
            $gameToto->setDateStart(new \DateTime());
            $gameToto->setDateEnd((new \DateTime())->modify('+1 hour'));
            $gameToto->setUser($userToto);
            $manager->persist($gameToto);
            
        // $product = new Product();
        // $manager->persist($product);

            // Ajout d'un nouvel utilisateur et jeu
            $userBelo = new Users();
            $userBelo->setPlayerName('Belo');
            $userBelo->setEmail('belo@gmail.com');
            $userBelo->getLastGame('2');
            $userBelo->setPassword('maman');
            $manager->persist($userBelo);

            // Sauvegardons l'utisateur crée dans un tableau
            $listUser[] = $userBelo;

            $gameBelo = new Game();
            $gameBelo->setScore(200);
            $gameBelo->setLevel(4);
            $gameBelo->setDuration(3600);
            $gameBelo->setDateStart(new \DateTime());
            $gameBelo->setDateEnd((new \DateTime())->modify('+1 hour'));
            $gameBelo->setUser($userBelo);
            $manager->persist($gameBelo);

            // Ajout d'un nouvel utilisateur et jeu
            $userPapa = new Users();
            $userPapa->setPlayerName('Papa');
            $userPapa->setEmail('pap@gmail.com');
            $userPapa->getLastGame('3');
            $userPapa->setPassword('papa23');
            $manager->persist($userPapa);

            $listUser[] = $userPapa;

            $gamePapa = new Game();
            $gamePapa->setScore(350);
            $gamePapa->setLevel(3);
            $gamePapa->setDuration(3600);
            $gamePapa->setDateStart(new \DateTime());
            $gamePapa->setDateEnd((new \DateTime())->modify('+1 hour'));
            $gamePapa->setUser($userPapa);
            $manager->persist($gameBelo);

        $manager->flush();
    }
}

