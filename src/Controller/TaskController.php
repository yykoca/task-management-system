<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/tasks')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $tasks = $entityManager->getRepository(Task::class)->findAll();

        $formattedTasks = [];
        foreach ($tasks as $task) {
            $users = [];
            foreach ($task->getUsers() as $user) {
                $users[] = [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                ];
            }

            $formattedTasks[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'users' => $users,
            ];
        }
        return $this->json($formattedTasks);
    }

    #[Route('/assign-user', name: 'task_assign_user')]
    #[IsGranted('ROLE_USER', message: 'You are not allowed to access this route.')]
    public function assign_user(#[CurrentUser] ?User $user, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $id = $request->query->get('id');
        
        $task = $entityManager->getRepository(Task::class)->findOneBy(['id' => $id]);
        $task->addUser($user);
        
        $entityManager->flush();
        return $this->json(['message' => 'Task added to user successfully']);
    }

    #[Route('/new', name: 'task_new', methods: ['POST'])]
    // #[IsGranted('ROLE_ADMIN', message: 'You are not allowed to access this route.')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $requestData = json_decode($request->getContent(), true);

        if (
            !isset($requestData['title']) || 
            !isset($requestData['description'])
            ) {
            throw new \InvalidArgumentException('All of "title" and "description" must be provided for create.');
        }

        try {
            $task = new Task();
            $task->setTitle($requestData['title'])
                ->setDescription($requestData['description']);

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->json(['message' => 'Saved new task with id ' . $task->getId()]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred while saving the task.'], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'task_show')]
    public function show(Task $task, EntityManagerInterface $entityManager): JsonResponse
    {   
        $task = $entityManager->getRepository(Task::class)->findOneBy(['id' => $task->getId()]);

        $users = [];
        foreach ($task->getUsers() as $user) {
            $users[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
            ];
        }

        return $this->json([
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'users' => $users,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['PATCH'])]
    // #[IsGranted('ROLE_ADMIN', message: 'You are not allowed to access this route.')]
    public function update(Task $task, Request $request, EntityManagerInterface $entityManager): Response
    { 
        try {
            $requestData = json_decode($request->getContent(), true);
    
            if (!isset($requestData['title']) && !isset($requestData['description'])) {
                throw new \InvalidArgumentException('At least one of "title" or "description" must be provided for update.');
            }

            if (isset($requestData['title']) && $requestData['title'] !== $task->getTitle()) {
                $task->setTitle($requestData['title']);
            }
    
            if (isset($requestData['description']) && $requestData['description'] !== $task->getDescription()) {
                $task->setDescription($requestData['description']);
            }

            $entityManager->flush();

            return $this->json([
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred while editing the task.'], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/delete', name: 'task_delete', methods: ['DELETE'])]
    // #[IsGranted('ROLE_ADMIN', message: 'You are not allowed to access this route.')]
    public function delete(Task $task, EntityManagerInterface $entityManager): Response
    {
        try {
            $id = $task->getId();
            $entityManager->remove($task);
            $entityManager->flush();

            return $this->json(['message' => 'Removed task was id ' . $id]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred while removing the task.'], Response::HTTP_BAD_REQUEST);
        }
    }
}
