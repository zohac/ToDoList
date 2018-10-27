<?php

// tests/AppBundle/ApplicationAvailabilityFunctionalTest.php

namespace Tests\AppBundle;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test if an url is found.
 */
class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsFound($url)
    {
        // create a client
        $client = self::createClient();

        // Replace the {user} in the url by an id of a user in DB
        if (\preg_match('#{user}#', $url)) {
            // Get the entityManager
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            // Get a User
            $user = $entityManager->getRepository(User::class)->findOneByUsername('user1');
            $url = \preg_replace('#{user}#', $user->getId(), $url);
        }
        // Replace the {task} in the url by an id of a task in DB
        if (\preg_match('#{task}#', $url)) {
            // Get the entityManager
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            // Get a User
            $task = $entityManager->getRepository(Task::class)->findOneByTitle('A great title');
            $url = \preg_replace('#{task}#', $task->getId(), $url);
        }
        // Get the url
        $client->request('GET', $url);

        // Test the status code
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * Return an array of url.
     */
    public function urlProvider()
    {
        return [
            ['/'],
            ['/tasks'],
            ['/tasks/create'],
            ['/tasks/{task}/edit'],
            ['/tasks/{task}/toggle'],
            ['/tasks/{task}/delete'],
            ['/users'],
            ['/users/create'],
            ['/users/{user}/edit'],
        ];
    }
}
