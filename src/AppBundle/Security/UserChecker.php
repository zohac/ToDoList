<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserChecker as BaseUserChecker;

/**
 * Check if the user does not have a role.
 */
class UserChecker extends BaseUserChecker
{
    /**
     * An instance of ObjectManager.
     *
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param ObjectManager $entityManager
     */
    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Check if the user does not have a role,
     * we give him one.
     *
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        // Verification of the user's role
        if (empty($user->getRoles())) {
            // If the user does not have a role, we give him one
            $user->setRoles(['ROLE_USER']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        parent::checkPreAuth($user);
    }

    /**
     * {@inheritdoc}
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        parent::checkPostAuth($user);
    }
}
