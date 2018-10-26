<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TaskController extends Controller
{
    /**
     * @Route("/tasks",
     *      name="task_list",
     *      methods={"GET"}
     * )
     */
    public function listAction(ObjectManager $entityManager)
    {
        $tasks = $entityManager->getRepository(Task::class)->findAllWhithAllEntities();

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/tasks/create",
     *      name="task_create",
     *      methods={"GET", "POST"}
     * )
     */
    public function createAction(Request $request, UserInterface $user, ObjectManager $entityManager)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($user);

            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit",
     *      name="task_edit",
     *      methods={"GET", "POST"},
     *      requirements={"id"="\d+"}
     * )
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle",
     *      name="task_toggle",
     *      methods={"GET"},
     *      requirements={"id"="\d+"}
     * )
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * Delete a task.
     *
     * @Route("/tasks/{id}/delete",
     *      name="task_delete",
     *      methods={"Get"},
     *      requirements={"id"="\d+"}
     * )
     *
     * @Security(
     *      "task.isAuthor(user)",
     *      message="Vous n'avez pas les droits pour supprimer cette tâche!"
     * )
     */
    public function deleteTaskAction(Task $task, ObjectManager $entityManager)
    {
        // Remove the task
        $entityManager->remove($task);
        $entityManager->flush();

        // Add the flash message
        $this->addFlash('success', 'La tâche a bien été supprimée.');

        // Redirect
        return $this->redirectToRoute('task_list');
    }
}
