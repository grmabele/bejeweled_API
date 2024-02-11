<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class EmailController extends AbstractController
{
    #[Route('/send/email', name: 'send_email')]
    public function sendEmail(MailerInterface $mailer): JsonResponse
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/EmailController.php',
        // ]);

        // Créer un nouvel e-mail
        $email = (new Email())
            ->from('grmabele@gmail.com') // Remplacez par votre adresse e-mail d'expéditeur
            ->to('soundouce.chibani@gmail.com ') // Remplacez par l'adresse e-mail du destinataire
            ->subject('Test Email from Symfony Mailer') // Définissez l'objet de l'e-mail
            ->text('Hello, this is a test email sent from a Symfony application!') // Définissez le corps de l'e-mail en tant que texte simple
            ->html('<p>Hello, this is a test email sent from a <b>Symfony</b> application!</p>'); // Optionnel : Définissez également le corps de l'e-mail en tant que HTML

        // Envoyer l'e-mail
        $mailer->send($email);

        // Retourner une réponse JSON
        return new JsonResponse('Email sent successfully');
        // return $this->json(['message' => 'Email sent successfully']);
    }
}
