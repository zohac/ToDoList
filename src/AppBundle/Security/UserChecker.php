<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Exception\AccountDeletedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

class UserChecker implements UserCheckerInterface
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        // Verification of the user's role
        if (empty($user->getRoles())) {
            // If the user does not have a role, we give him a role
            $user->setRoles(['ROLE_USER']);

            //var_dump($this->entityManager); die;
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        // user is deleted, show a generic Account Not Found message.
        if ($user->isDeleted()) {
            throw new AccountDeletedException('...');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        // user account is expired, the user may be notified
        if ($user->isExpired()) {
            throw new AccountExpiredException('...');
        }
    }
}
