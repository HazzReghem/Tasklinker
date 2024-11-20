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

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }
}
