<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Psr\Log\LoggerInterface;
use App\Service\MessageGenerator;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Symfony\Component\HttpKernel\Attribute\Cache;


class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test', methods:['GET', 'HEAD'])]
    #[Cache(public: true, maxage: 600, smaxage: 600, mustRevalidate: true)]
    public function index(Request $request): Response
    {
        $response = $this->render('test/index.html.twig');
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 1);
        return $response;
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/TestController.php',
        // ]);
    }
}
