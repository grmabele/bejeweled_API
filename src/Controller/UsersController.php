<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UsersController extends AbstractController
{
    /**
     * Cette méthode permet de récupérer l'ensemble des users. 
     *
     * @param UsersRepository $authorRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/users', name: 'users', methods: ['GET'])]
    public function getUsers(UsersRepository $usersRepository, SerializerInterface $serializer): JsonResponse
    {
        
        $users = $usersRepository->findAll();
        
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'getUsers']);

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }


    /**
     * Cette méthode permet de récupérer un user en particulier en fonction de son id_user. 
     *
     * @param Users $users
     * @param SerializerInterface $serializer
     * @param UsersRepository $usersRepository
     * @param int $id_user
     * @return JsonResponse
     */
    #[Route('/api/users/{id_user}', name: 'detailUsers', methods: ['GET'])]
    public function getDetailUsers(Users $users, SerializerInterface $serializer, UsersRepository $usersRepository, int $id_user): JsonResponse
        {
            // $users = $usersRepository->find($id_user);
            // if (!$users) {
            //     // Si le jeu n'est pas trouvé, retournez une réponse 404.
            //     return new JsonResponse(["error" => "User not found."], Response::HTTP_NOT_FOUND);
            // }

            // Si le jeu est trouvé, sérialisez-le et retournez-le.
            $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'getUsers']);
            return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
        }

    /**
     * Cette méthode permet de créer un nouvel utilisateur (user). Elle ne permet pas 
     * d'associer directement des jeu à cet utilisateur. 
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    #[Route('/api/users', name: 'createUser', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
       
        // Désérialiser le contenu de la requête en une nouvelle instance de Users
        $users = $serializer->deserialize($request->getContent(), Users::class, 'json');

        $users->setLastGameId(null);

        // Persister l'instance de Users
        $em->persist($users);
        $em->flush(); // Sauvegarder les changements dans la base de données

        // Sérialiser l'instance de Users pour la réponse
        $jsonUser = $serializer->serialize($users, 'json', ['groups' => 'getUsers'], );
        $location = $urlGenerator->generate('detailUsers', ['id_user' => $users->getIdUser()], UrlGeneratorInterface::ABSOLUTE_URL);
        
        // Retourner la réponse avec l'utilisateur créé
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }

}