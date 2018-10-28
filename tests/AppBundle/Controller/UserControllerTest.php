<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test the user controller.
 */
class UserControllerTest extends WebTestCase
{
    /**
     * Test the route /users with an authenticated user.
     */
    public function testListAction()
    {
        // Create an authenticated client
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW' => 'Aa@123',
        ]);

        // Request the route
        $client->request('GET', '/users');

        // Test
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test the route /users/create with an authenticated user.
     */
    public function testCreateActionWithGoodValue()
    {
        // Create an authenticated client
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW' => 'Aa@123',
        ]);

        // Request the route
        $crawler = $client->request('GET', '/users/create');

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
        $form['user[username]'] = 'userTest';
        $form['user[plainPassword][first]'] = 'Aa@123';
        $form['user[plainPassword][second]'] = 'Aa@123';
        $form['user[email]'] = 'userTest@test.com';
        $form['user[roles]'] = 'ROLE_USER';

        // submit the form
        $crawler = $client->submit($form);

        // Test
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * Test the route /users/{id}/edit with an authenticated user.
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
        // Get a user
        $user = $entityManager->getRepository(User::class)->findOneByUsername('userTest');
        // Edit the route
        $route = '/users/'.$user->getId().'/edit';

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
        $form['user_update[username]'] = 'userTestModified';
        $form['user_update[email]'] = 'userTest@test.com';
        $form['user_update[roles]'] = 'ROLE_USER';

        // submit the form
        $crawler = $client->submit($form);

        // Test
        $this->assertTrue($client->getResponse()->isRedirect());

        // Get a user
        $user = $entityManager->getRepository(User::class)->findOneByUsername('userTestModified');
        // Remove the user
        $entityManager->remove($user);
        $entityManager->flush();
    }
}
