<?php

namespace App\Controller;

use App\Entity\TaskEntity;
use App\Form\TaskEntityType;
use App\Repository\TaskEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_index')]
    public function index(TaskEntityRepository $taskRepository): Response
    {
        // Récupère toutes les tâches
        $tasks = $taskRepository->findAll();

        // Retourne une réponse avec la vue pour afficher les tâches
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new TaskEntity();
        $form = $this->createForm(TaskEntityType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste la tâche dans la base de données
            $entityManager->persist($task);
            $entityManager->flush();

            // Redirige vers la liste des tâches après la création
            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function edit(TaskEntity $task, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskEntityType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre les modifications dans la base de données
            $entityManager->flush();

            // Redirige vers la page de la tâche après modification
            return $this->redirectToRoute('task_view', ['id' => $task->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]);
    }

    #[Route('/tasks/{id}', name: 'task_view')]
    public function view(TaskEntity $task): Response
    {
        return $this->render('task/view.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function delete(TaskEntity $task, EntityManagerInterface $entityManager): Response
    {
        // Supprime la tâche de la base de données
        $entityManager->remove($task);
        $entityManager->flush();

        // Redirige vers la liste des tâches après suppression
        return $this->redirectToRoute('task_index');
    }
}
