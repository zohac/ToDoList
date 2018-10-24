<?php

namespace tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    /**
     * Test the hydratation of the Entity and the relationship between entity.
     */
    public function testEntityTask()
    {
        $task = new Task();
        $task->setTitle('A title');
        $task->setContent('A great content!');
        $task->setCreatedAt(new \Datetime('2018-10-24'));
        $task->toggle(!$task->isdone());

        $this->assertEquals('A title', $task->getTitle());
        $this->assertEquals('A great content!', $task->getContent());
        $this->assertEquals(new \Datetime('2018-10-24'), $task->getCreatedAt());
        $this->assertEquals('Anonyme', $task->getUser()->getUsername());
        $this->assertTrue($task->isDone());

        $user = new User();
        $task->setUser($user);
        $this->assertEquals($user, $task->getUser());
    }
}
