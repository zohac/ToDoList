<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test the task controller.
 */
class TaskControllerTest extends WebTestCase
{
    /**
     * Test the route /tasks with an authenticated user.
     */
    public function testListAction()
    {
        // Create an authenticated client
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW' => 'Aa@123',
        ]);

        // Request the route
        $crawler = $client->request('GET', '/tasks');

        // Test
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("A great title")')->count()
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test the route /tasks/create with an authenticated user.
     */
    public function testCreateActionWithGoodValue()
    {
        // Create an authenticated client
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW' => 'Aa@123',
        ]);

        // Request the route
        $crawler = $client->request('GET', '/tasks/create');

        // Test
        $this->assertEquals(
            1,
            $crawler->filter('form')->count()
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Select the form
        $form = $crawler->selectButton('Ajouter')->form();

        // set some values
        $form['task[title]'] = 'A test title';
        $form['task[content]'] = 'A great content!';

        // submit the form
        $crawler = $client->submit($form);

        // Test
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * Test the route /tasks/{id}/edit with an authenticated user.
     */
    public function testEditActionWithGoodValue()
    {
        // Create an authenticated client
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW' => 'Aa@123',
        ]);

        // Get the entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        // Get a task
        $task = $entityManager->getRepository(Task::class)->findOneByTitle('A test title');
        // Edit the route
        $route = '/tasks/'.$task->getId().'/edit';

        // Request the route
        $crawler = $client->request('GET', $route);

        // Test
        $this->assertEquals(
            1,
            $crawler->filter('form')->count()
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Select the form
        $form = $crawler->selectButton('Modifier')->form();

        // set some values
        $form['task[title]'] = 'A modified title test';

        // submit the form
        $crawler = $client->submit($form);

        // Test
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * Test the route /tasks/{id}/toggle with an authenticated user.
     */
    public function testToggleAction()
    {
        // Create an authenticated client
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW' => 'Aa@123',
        ]);

        // Get the entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        // Get a task
        $task = $entityManager->getRepository(Task::class)->findOneByTitle('A modified title test');
        // Edit the route
        $route = '/tasks/'.$task->getId().'/toggle';

        // Test
        $this->assertFalse($task->isDone());

        // Request the route
        $client->request('GET', $route);

        // Test
        $this->assertTrue($task->isDone());
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * Test the route /tasks/{id}/delete with an authenticated user.
     */
    public function testDeleteWithRoleUserAction()
    {
        // Create an authenticated client
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user2',
            'PHP_AUTH_PW' => 'Aa@123',
        ]);

        // Get the entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        // Get a task
        $task = $entityManager->getRepository(Task::class)->findOneByTitle('A modified title test');
        // Edit the route
        $route = '/tasks/'.$task->getId().'/delete';
        // Request the route
        $client->request('GET', $route);

        // Get a task
        $task = $entityManager->getRepository(Task::class)->findOneByTitle('A modified title test');

        // Test
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * Test the route /tasks/{id}/delete with an authenticated user.
     */
    public function testDeleteAction()
    {
        // Create an authenticated client
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW' => 'Aa@123',
        ]);

        // Get the entityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        // Get a task
        $task = $entityManager->getRepository(Task::class)->findOneByTitle('A modified title test');
        // Edit the route
        $route = '/tasks/'.$task->getId().'/delete';
        // Request the route
        $client->request('GET', $route);

        // Get a task
        $task = $entityManager->getRepository(Task::class)->findOneByTitle('A modified title test');

        // Test
        $this->assertNull($task);
        $this->assertTrue($client->getResponse()->isRedirect());
    }
}
