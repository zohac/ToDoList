<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 *  form to add a task.
 */
class TaskType extends AbstractType
{
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // To prevent an error under Codacy
        $options = null;

        // Build the form
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le titre ne peu comporter que {{ limit }} caractères',
                    ]),
                    new NotBlank(),
                ],
                'label' => 'Titre de la tâche',
            ])
            ->add('content', TextareaType::class, [
                'constraints' => [new NotBlank()],
                'label' => 'Description de la tâche',
            ])
        ;
    }
}
