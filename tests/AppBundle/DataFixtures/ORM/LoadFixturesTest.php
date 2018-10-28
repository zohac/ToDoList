<?php

namespace Tests\AppBundle;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\DataFixtures\ORM\LoadFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fixtures class.
 */
class LoadFixturesTest extends WebTestCase
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Initialise variables.
     */
    protected function setUp()
    {
        parent::setUp();

        // Now, mock the repository so it returns the mock of the user
        $this->userRepository = $this->createMock(UserRepository::class);

        // Last, mock the EntityManager to return the mock of the repository
        $this->entityManager = $this->createMock(ObjectManager::class);
    }

    /**
     * Test loadFixtures.
     */
    public function testLoad()
    {
        // Define findOneBy method
        $this->userRepository
            ->expects($this->any())
            ->method('findOneBy')
            ->willReturn(new User());

        // Define getRepository method
        $this->entityManager
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->userRepository);

        // Define persist method
        $this->entityManager
            ->expects($this->any())
            ->method('persist');

        // Define flush method
        $this->entityManager
            ->expects($this->any())
            ->method('flush');

        // Create client
        $client = static::createClient();

        // Get the entityManager
        $passwordEncoder = $client->getContainer()->get('security.password_encoder');

        $fixtures = new LoadFixtures($passwordEncoder);
        $fixtures->load($this->entityManager);
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
