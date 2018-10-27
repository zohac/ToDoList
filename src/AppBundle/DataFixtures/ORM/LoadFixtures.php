<?php

// src/AppBundle/DataFixtures/ORM/LoadUser.php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Load Fixtures in DB.
 */
class LoadFixtures extends AbstractFixture
{
    /**
     * An instance of UserPasswordEncoderInterface.
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * Constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Load the fixture.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $entityManager)
    {
        // Add Users
        $users = Yaml::parseFile('src/AppBundle/DataFixtures/Data/User.yml');
        foreach ($users as $userData) {
            // Persist User
            $entityManager->persist($this->loadUser($userData));
        }

        // Add Tasks
        $tasks = Yaml::parseFile('src/AppBundle/DataFixtures/Data/Task.yml');
        foreach ($tasks as $taskData) {
            // Persist Task
            $entityManager->persist($this->loadUser($taskData));
        }

        // Save the entities
        $entityManager->flush();
    }

    /**
     * Load a user.
     *
     * @param array $userData
     *
     * @return User
     */
    public function loadUser(array $userData): User
    {
        // Create a User
        $user = new User();
        // Set the username
        $user->setUsername($userData['username']);
        // Set the email
        $user->setEmail($userData['email']);
        // Set the roles
        if (isset($userData['roles'])) {
            $user->setRoles($userData['roles']);
        }
        // Set the password
        $user->setPassword($this->encoder->encodePassword($user, $userData['password']));
        // Return the new user
        return $user;
    }

    /**
     * Load a task.
     *
     * @param array $taskData
     *
     * @return Task
     */
    public function loadTask(array $taskData): Task
    {
        // Create a task
        $task = new Task();
        // Set the title
        $task->setTitle($userData['title']);
        // Set the content
        $task->setContent($userData['content']);
        // Set the title
        if (isset($taskData['user'])) {
            $user = $entityManager->getRepository(User::class)->findByUsername($taskData('user'));
            $task->setUser($user);
        }
        // Return the task
        return $task;
    }
}
