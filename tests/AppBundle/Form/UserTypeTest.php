<?php

namespace tests\AppBundle\Form;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

/**
 * Unit testUnit test of the UserType form.
 */
class UserTypeTest extends TypeTestCase
{
    /**
     * Test valid data.
     */
    public function testSubmitValidData()
    {
        $userToTest = new User();

        $formData = [
            'username' => 'test',
            'email' => 'test@test.fr',
            'plainPassword' => [
                'first' => 'aGreatPassword',
                'second' => 'aGreatPassword',
            ],
            'roles' => 'ROLE_USER',
        ];

        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(UserType::class, $userToTest);

        // Create new user
        $user = new User();
        $user->setUsername('test');
        $user->setEmail('test@test.fr');
        $user->setPlainPassword('aGreatPassword');
        $user->setRoles(['ROLE_USER']);

        // submit the data to the form directly
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($user, $form->getData());

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
