<?php

namespace InfinityBase\Service;

use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Form\Form;

trait AbstractServiceTrait
{

    /**
     * @var AbstractMapper
     */
    private $mapper;

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
    protected function addMessage($message, $namespace = 'default')
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