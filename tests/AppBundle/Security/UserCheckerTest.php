<?php

namespace tests\AppBundle\Entity;

use AppBundle\Entity\User;
use AppBundle\Security\UserChecker;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCheckerTest extends KernelTestCase
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * Initialise variables.
     */
    protected function setUp()
    {
        parent::setUp();

        // Last, mock the EntityManager to return the mock of the repository
        $this->entityManager = $this->createMock(ObjectManager::class);

        // Define persist method
        $this->entityManager
            ->expects($this->any())
            ->method('persist');

        // Define flush method
        $this->entityManager
            ->expects($this->any())
            ->method('flush');
    }

    /**
     * Test CheckPreAuth.
     */
    public function testCheckPreAuth()
    {
        $user = new User();

        $userChecker = new UserChecker($this->entityManager);
        $userChecker->checkPreAuth($user);
    }

    /**
     * Test CheckPreAuth without User.
     */
    public function testCheckPreAuthWithoutUser()
    {
        $user = $this->getFakeUser();

        $userChecker = new UserChecker($this->entityManager);
        $userChecker->checkPreAuth($user);
    }

    /**
     * Test checkPostAuth without User.
     */
    public function testCheckPostAuth()
    {
        $user = $this->getFakeUser();

        $userChecker = new UserChecker($this->entityManager);
        $userChecker->checkPostAuth($user);
    }

    /**
     * Create a fake User.
     */
    public function getFakeUser()
    {
        return new class() implements UserInterface {
            public function getRoles()
            {
                return;
            }

            public function getPassword()
            {
                return;
            }

            public function getSalt()
            {
                return;
            }

            public function getUsername()
            {
                return;
            }

            public function eraseCredentials()
            {
                return;
            }
        };
    }

    /**
     * Unset variables.
     */
    protected function tearDown()
    {
        parent::tearDown();

        // avoid memory leaks
        $this->entityManager = null;
    }
}
