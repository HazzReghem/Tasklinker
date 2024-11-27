<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\ProjectRepository;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Enum\ProjectStatus;
use App\Repository\TaskRepository;
use App\Entity\Task;
use App\Form\TaskType;


class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findBy(['status' => ProjectStatus::Active->value]);

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/project/new', name: 'add_project')]
    public function addProject(Request $request, EntityManagerInterface $em): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('project_detail', ['id' => $project->getId()]);
        }

        return $this->render('project/add_project.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/project/{id}', name: 'project_detail')]
    public function show(int $id, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Le projet demandé n\'existe pas.');
        }

        $tasks = $project->getTasks();

        $employee = $project->getEmployee();

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            'employee' => $employee,
        ]);
    }

    #[Route('/project/{id}/edit_project', name: 'edit_project')]
    public function editProject(int $id, Request $request, EntityManagerInterface $em, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Le projet demandé n\'existe pas.');
        }

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            return $this->redirectToRoute('project_detail', ['id' => $project->getId()]);
        }

        return $this->render('project/edit_project.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/project/{id}/delete', name: 'delete_project', methods: ['POST'])]
    public function deleteProject(int $id, ProjectRepository $projectRepository, EntityManagerInterface $em): RedirectResponse
    {
        $project = $projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('La tâche demandée n\'existe pas.');
        }

        $project->setStatus(ProjectStatus::Archived); 

        foreach ($project->getTasks() as $task) {
            $task->setEmployee(null);
            $em->persist($task);
        }

        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    // ----------------- TASKS FUNCTIONS ----------------------

    #[Route('/project/{id}/add_task', name: 'add_task')]
    public function addTask(int $id, Request $request, EntityManagerInterface $em, ProjectRepository $projectRepository): Response    {
        $project = $projectRepository->find($id);
        
        if (!$project) {
            throw $this->createNotFoundException('Le projet demandé n\'existe pas.');
        }

        // Créer une nouvelle tâche
        $task = new Task();
        $task->setProject($project);

        //traiter le formulaire
        $form = $this->createForm(TaskType::class, $task);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('project_detail', ['id' => $id]);
        }

        return $this->render('project/add_task.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/task/{id}/edit', name: 'edit_task')]
    public function editTask(int $id, Request $request, EntityManagerInterface $em, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException('La tâche demandée n\'existe pas.');
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            return $this->redirectToRoute('edit_task', ['id' => $task->getId()]);
        }

        return $this->render('project/edit_task.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}/delete', name: 'delete_task', methods: ['POST'])]
    public function deleteTask(int $id, TaskRepository $taskRepository, EntityManagerInterface $em): RedirectResponse
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException('La tâche demandée n\'existe pas.');
        }

        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('project_detail', ['id' => $task->getProject()->getId()]);
    }

}
