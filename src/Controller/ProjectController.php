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
use App\Entity\TaskRepository;
use App\Entity\Task;
use App\Form\TaskType;



class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
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
}
