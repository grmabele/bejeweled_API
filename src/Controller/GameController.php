<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Entity\Game;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use App\Message\SendEmailMessage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GameController extends AbstractController
{
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus) {
        $this->messageBus = $messageBus;
    }

    #[Route('api/games', name: 'games', methods: ['GET'])]
    public function getGames(GameRepository $gameRepository, SerializerInterface $serializer): JsonResponse
    {
        $games = $gameRepository->findAll();
        $jsonGame = $serializer->serialize($games, 'json', ['groups' => 'getGames']);
        
        return new JsonResponse($jsonGame, Response::HTTP_OK, [], true);
    }

     /**
     * Cette méthode permet de récupérer un jeu (Game) en particulier en fonction de son id_game. 
     *
     * @param Game $game
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/games/{id_game}', name: 'detailGame', methods: ['GET'])]
    public function getDetailGame(Game $game, SerializerInterface $serializer): JsonResponse
        {
            // $id_game = (int) $id_game; // Convertissez explicitement en entier
            // $game = $gameRepository->find($id_game);
            // if (!$game) {
            //     return new JsonResponse(["error" => "Jeu introuvable."], Response::HTTP_NOT_FOUND);
            // }

            $jsonGame = $serializer->serialize($game, 'json', ['groups' => 'getGames']);
            return new JsonResponse($jsonGame, Response::HTTP_OK, [], true);
        }


    #[Route('/api/game/{idGame}', name: 'getDeleteGame', methods: ['DELETE'])]
    public function getDeleteGame(GameRepository $gameRepository, int $id_game, EntityManagerInterface $em): JsonResponse
        {
            $game = $gameRepository->find($id_game);
            if (!$game) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }
            $em->remove($game);
            $em->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }


    /**
     * Cette méthode permet d'insérer un nouveau jeu (Game). 
     * 
     * Le paramètre id_user est géré "à la main", pour créer l'association
     * entre un jeu (Game) et un utilisateur (user). 
     * S'il ne correspond pas à un utilisateur (user) valide, alors le jeu (Game) sera considéré comme n'ayant pas d'utilisateur. 
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $urlGenerator
     * @param AuthorRepository $authorRepository
     * @return JsonResponse
     */
    #[Route('/api/games', name: 'createGame', methods: ['POST'])]
    public function createGame(Request $request, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, SerializerInterface $serializer, UsersRepository $usersRepository): JsonResponse
    {
        $game = $serializer->deserialize($request->getContent(), Game::class, 'json');
        
        // Essayez d'abord de récupérer l'utilisateur connecté
        $user = $this->getUser();
        
        // Si aucun utilisateur n'est connecté, utilisez le dernier utilisateur inséré comme secours
        if (!$user) {
            $user = $usersRepository->findOneBy([], ['id_user' => 'DESC']);
            if (!$user) {
                // Si aucun utilisateur n'est trouvé, renvoyez une erreur
                return new JsonResponse(['error' => 'Aucun utilisateur disponible pour associer au jeu'], Response::HTTP_BAD_REQUEST);
            }
        }
    
        // Associez l'utilisateur récupéré au jeu
        $game->setUser($user);
    
        $em->persist($game);
        $em->flush();
    
        $jsonGame = $serializer->serialize($game, 'json', ['groups' => 'getGames']);
        $location = $urlGenerator->generate('detailGame', ['id_game' => $game->getIdGame()], UrlGeneratorInterface::ABSOLUTE_URL);
        
        return new JsonResponse($jsonGame, Response::HTTP_CREATED, ["Location" => $location], true);

        $gameSummary = "Votre résumé de jeu"; // Générez le résumé du jeu ici
        // $delay = 3600 * 1000; // Délai en millisecondes (1 heure)

         $delay = 10 * 1000; // 10 secondes * 1000 millisecondes/seconde


        $envelope = new Envelope(
            new SendEmailMessage($user->getEmail(), $gameSummary),
            [
                new DelayStamp($delay)
            ]
        );

        $this->messageBus->dispatch($envelope);
    }
    
    
    // Mise à jour du score

    #[Route('/api/games/score/{id_game}', name: 'updateGameScore', methods: ['PATCH'])]
    public function updateGameScore(int $id_game, Request $request, GameRepository $gameRepository, EntityManagerInterface $em): JsonResponse
        {
            $game = $gameRepository->find($id_game);
            if (!$game) {
                return new JsonResponse(["error" => "Game not found."], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            
            // Vérification que la clé 'score' est présente dans $data
            if (!isset($data['score'])) {
                return new JsonResponse(["error" => "The 'score' field is required."], Response::HTTP_BAD_REQUEST);
            }
            
            
            $game->setScore($data['score']);
            $em->persist($game);
            $em->flush();

            return new JsonResponse(["success" => "Score updated."], Response::HTTP_OK);
        }

        // Affichage des 6 meilleurs scores
        #[Route('/api/games/top-scores', name: 'getTopScores', methods: ['PATCH'])]
        public function getTopScores(GameRepository $gameRepository, SerializerInterface $serializer): JsonResponse
        {
            $topScores = $gameRepository->findTopScores(6);
            // //var_dump($topScores);
            // die; // Arrêtez l'exécution pour voir le résultat du var_dump
            $jsonTopScores = $serializer->serialize($topScores, 'json', ['groups' => 'getGames']);
            
            return new JsonResponse($jsonTopScores, Response::HTTP_OK, [], true);
        }


        // #[Route('/api/games', name: 'createGame', methods: ['POST'])]
    // public function createGame(Request $request, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, SerializerInterface $serializer, UsersRepository $usersRepository): JsonResponse
    // {
    //     $game = $serializer->deserialize($request->getContent(), Game::class, 'json');
        
    //     // Récupérer le dernier utilisateur inséré
    //     $lastUser = $usersRepository->findOneBy([], ['id_user' => 'DESC']);
        
    //     if (!$lastUser) {
    //         // Gérer le cas où il n'y a pas encore d'utilisateur
    //         return new JsonResponse(['error' => 'Aucun utilisateur disponible pour associer au jeu'], Response::HTTP_BAD_REQUEST);
    //     }

    //     // Associer l'utilisateur récupéré au jeu
    //     $game->setUser($lastUser);

    //     $em->persist($game);
    //     $em->flush();

    //     $jsonGame = $serializer->serialize($game, 'json', ['groups' => 'getGames']);
    //     $location = $urlGenerator->generate('detailGame', ['id_game' => $game->getIdGame(), UrlGeneratorInterface::ABSOLUTE_URL]);
        
    //     return new JsonResponse($jsonGame, Response::HTTP_CREATED, ["Location" => $location], true);
    // }

        

}
