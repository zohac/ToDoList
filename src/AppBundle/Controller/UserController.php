<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\UserUpdateType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    /**
     * @Route("/users",
     *      name="user_list",
     *      methods={"GET"}
     * )
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listAction(ObjectManager $entityManager)
    {
        return $this->render('user/list.html.twig', [
            'users' => $entityManager->getRepository(User::class)->findAll(),
        ]);
    }

    /**
     * @Route("/users/create",
     *      name="user_create",
     *      methods={"GET", "POST"}
     * )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit",
     *      name="user_edit",
     *      methods={"GET", "POST"},
     *      requirements={"id"="\d+"}
     * )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param User    $user
     * @param Request $request
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm(UserUpdateType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null !== $user->getPlainPassword()) {
                $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
