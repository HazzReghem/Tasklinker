<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\EmployeeRepository;
use App\Entity\Employee;
use App\Form\EmployeeType;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        return $this->render('team/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/employee/{id}/edit', name: 'edit_employee')]
    public function editEmployee(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $employee = $em->getRepository(Employee::class)->find($id);

        if (!$employee) {
            throw $this->createNotFoundException('L\'employé demandé n\'existe pas.');
        }

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_team');
        }

        return $this->render('team/edit_employee.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }

    #[Route('/employee/{id}/delete', name: 'delete_employee', methods: ['POST'])]
    public function deleteEmployee(int $id, EmployeeRepository $employeeRepository, EntityManagerInterface $em): RedirectResponse
    {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            throw $this->createNotFoundException('La tâche demandée n\'existe pas.');
        }

        foreach ($employee->getTasks() as $task) {
            $task->setEmployee(null); 
            $em->persist($task);
        }

        $em->remove($employee);
        $em->flush();

        return $this->redirectToRoute('app_team');
    }
}
