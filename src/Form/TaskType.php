<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Enum\TaskStatus;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de la tâche", 
                'required' => true,
                ])
            ->add('description', TextType::class, [
                'label' => "Description", 
                'required' => false,
                ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date",
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => "Statut",
                'choices' => [
                    'To Do' => TaskStatus::ToDo,
                    'Doing' => TaskStatus::Doing,
                    'Done' => TaskStatus::Done,
                ],
                'required' => true,
            ])
            ->add('employee', EntityType::class, [
                'label' => 'Membre',
                'class' => Employee::class,
                'choice_label' => function (Employee $employee) {
                    return $employee->getFirstName() . ' ' . $employee->getLastName();
                },
                'required' => false,
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
