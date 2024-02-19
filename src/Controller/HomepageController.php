<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomepageController extends AbstractController {
    public function __construct(
        private HttpClientInterface $client,
    ) {}

    #[Route('/', name: 'app_homepage')]
    public function homepage(TaskRepository $repository)
    {
        $tasks = $repository->findAll();
        $quote = $this->fetchQuote();
    
        return $this->render('homepage.html.twig', [
            'tasks' => $tasks,
            'quote' => $quote,
        ]);
    }

    public function fetchQuote()
    {
        $response = $this->client->request(
            'GET',
            'https://api.gameofthronesquotes.xyz/v1/random'
        );

        $content = $response->getContent();
        $content = $response->toArray();

        return [ 
            "sentence" => $content["sentence"],
            "character" => $content["character"]["name"],
        ];
    }
}
