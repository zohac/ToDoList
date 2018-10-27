<?php

namespace Tests\AppBundle;

use AppBundle\Entity\User;
use AppBundle\DataFixtures\ORM\LoadFixtures;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fixtures class.
 */
class LoadFixturesTest extends WebTestCase
{
    public function testLoad()
    {
        $client = static::createClient();

        // Get the entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $passwordEncoder = $client->getContainer()->get('security.password_encoder');

        $purger = new ORMPurger($entityManager);
        $purger->purge();

        $fixtures = new LoadFixtures($passwordEncoder);
        $fixtures->load($entityManager);

        // Get a user
        $user = $entityManager->getRepository(User::class)->findOneByUsername('user1');

        $this->assertInstanceOf(User::class, $user);
    }
}
