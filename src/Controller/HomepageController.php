<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController {
    #[Route('/', name: 'app_homepage')]
    public function homepage(TaskRepository $repository)
    {
        $tasks = $repository->findAll();
    
        return $this->render('homepage.html.twig', [
            'tasks' => $tasks,
        ]);
    }

}
