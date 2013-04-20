<?php

namespace InfinityBase\Service;

use Zend\Form\Form;
use Zend\Form\FormElementManager;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

trait AbstractServiceTrait
{

    /**
     * @var AbstractMapper
     */
    private $mapper;

    /**
     * @var FormElementManager
     */
    private $formElementManager;

    /**
     * @var array
     */
    private $form = array();

    /**
     * @var FlashMessenger
     */
    private $messenger;

    /**
     * Add a message to the flashMessenger
     *
     * @param string|array $message
     * @param string $namespace
     */
    protected function addMessage($message, $namespace = FlashMessenger::NAMESPACE_DEFAULT)
    {
        // Load flashMessenger
        if (null === $this->messenger) {
            $this->messenger = $this->getServiceLocator()
                    ->get('ControllerPluginManager')
                    ->get('flashMessenger');
        }

        // Set current namespace
        $this->messenger->setNamespace($namespace);

        // Add messages
        $message = (array) $message;
        foreach ($message as $value) {
            $this->messenger->addMessage($value);
        }
    }

    /**
     * Retrieve form element manager
     *
     * @return FormElementManager
     */
    protected function getFormElementManager()
    {
        if (null === $this->formElementManager) {
            $this->formElementManager = $this->getServiceLocator()
                    ->get('FormElementManager');
        }
        return $this->formElementManager;
    }

    /**
     * Retrieve a form
     *
     * @param string $name
     * @return Form
     */
    protected function getForm($name)
    {
        if (!isset($this->form[$name])) {
            $this->form[$name] = $this->getServiceLocator()
                    ->get($this->getModuleNamespace() . '\Form\\' . $name);
        }
        return $this->form[$name];
    }

    /**
     * Retrieve the mapper
     *
     * @return AccountsMapper
     */
    protected function getMapper()
    {
        if (null === $this->mapper) {
            $this->mapper = $this->getServiceLocator()
                    ->get($this->getModuleNamespace() . '\Mapper\\' . $this->getEntityName());
        }
        return $this->mapper;
    }

}

