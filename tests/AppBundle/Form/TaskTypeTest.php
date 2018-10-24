<?php

namespace tests\AppBundle\Form;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

/**
 * Unit testUnit test of the TaskType form.
 */
class TaskTypeTest extends TypeTestCase
{
    /**
     * Test valid data.
     */
    public function testSubmitValidData()
    {
        $taskToTest = new Task();

        $formData = [
            'title' => 'A title',
            'content' => 'A great content!',
        ];

        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(TaskType::class, $taskToTest);

        // Create new Task
        $task = new Task();
        $task->setTitle('A title');
        $task->setContent('A great content!');

        // submit the data to the form directly
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        // Retrieves the form view and form data in array
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            // Verifies that the keys of the form exist
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * Add an extension to validate data.
     */
    protected function getExtensions()
    {
        return [new ValidatorExtension(Validation::createValidator())];
    }
}
