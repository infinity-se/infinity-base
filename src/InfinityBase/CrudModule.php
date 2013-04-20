<?php

namespace InfinityBase;

use DoctrineModule\Validator\ObjectExists;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

abstract class CrudModule implements ServiceProviderInterface
{

    use Entity\EntityAwareTrait;

    /**
     * @var array
     */
    protected $entityNames = array();

    /**
     * @var array
     */
    private $formTypes = array('Create', 'Edit', 'Delete');

    public function getServiceConfig()
    {
        // Create factories
        $factories = array();
        foreach ($this->entityNames as $entityName) {
            foreach ($this->formTypes as $formType) {
                $factories[$this->getModuleNamespace() . '\\Form\\' . $formType . $entityName] = function ($serviceManager) use ($formType, $entityName) {
                            $function = 'create' . $formType . 'Form';
                            return $this->$function($serviceManager, $entityName);
                        };
            }
        }

        return array('factories' => $factories);
    }

    protected function createCreateForm($serviceManager, $entityName)
    {
        // Create form
        return $this->createForm($serviceManager, $entityName, false);
    }

    protected function createEditForm($serviceManager, $entityName)
    {
        // Create form
        $form = $this->createForm($serviceManager, $entityName);

        // Change form submission name
        $submit = $form->get('submit');
        $submit->setValue('Save Account');

        return $form;
    }

    protected function createDeleteForm($serviceManager, $entityName)
    {
        // Create form
        $form = $this->createForm($serviceManager, $entityName);

        // Remove elements
        foreach ($form->getElements() as $element) {
            switch ($element->getName()) {
                case 'submit':
                    $element->setValue('Delete Account');
                case 'id':
                case 'security':
                    continue;
                default:
                    $form->remove($element->getName());
                    break;
            }
        }

        $form->setValidationGroup(array('id'));

        return $form;
    }

    protected function createForm($serviceManager, $entityName, $validateId = true)
    {
        // Create entity
        $entityClass = $this->getModuleNamespace() . '\Entity\\' . $entityName;
        $entity      = new $entityClass;

        // Create hydrator and repository
        $entityManager  = $serviceManager->get('Doctrine\ORM\EntityManager');
        $entityHydrator = new DoctrineEntity($entityManager, $entityClass);

        // Create form
        $annotationBuilder = new AnnotationBuilder($entityManager);
        $form              = $annotationBuilder->createForm($entity);
        $form->setHydrator($entityHydrator);

        // Validate id
        if ($validateId) {

            // Add id as input
            $form->add(array(
                'type'     => 'hidden',
                'name'     => 'id',
                'required' => true,
            ));

            // Create validator
            $entityRepository      = $entityManager->getRepository($entityClass);
            $objectExistsValidator = new ObjectExists(array(
                'object_repository' => $entityRepository,
                'fields'            => 'id',
            ));

            // Add validator to input
            $idInput = $form->getInputFilter()->get('id');
            $idInput->getValidatorChain()->addValidator($objectExistsValidator);
        }

        // Bind entity form
        $form->bind($entity);
        return $form;
    }

}
