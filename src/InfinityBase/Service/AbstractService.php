<?php

namespace InfinityBase\Service;

use InfinityBase\ServiceManager\AbstractServiceLocatorAware;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Form\Form;

abstract class AbstractService extends AbstractServiceLocatorAware
{

    /**
     * @var string
     */
    private $abstractType = 'service';

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
     * PROTECTED METHODS
     */

    /**
     * Flush the entityManager and handle errors
     *
     * @return boolean
     */
    protected function save($successMessage, $failureMessage)
    {
        try {
            $this->getEntityManager()->flush();
            $this->addMessage($successMessage, 'success');
            return true;
        } catch (\Exception $e) {
            $this->addMessage($failureMessage, 'error');

            // Check exception type
            if ($e instanceof \Doctrine\DBAL\DBALException) {
                $previous = $e->getPrevious();
                if ($previous instanceof \Doctrine\DBAL\Driver\Mysqli\MysqliException) {

                    // Check exception type
                    switch ($previous->getCode()) {
                        case 1062:
                            $this->addMessage(
                                    'You are using details that already exist.',
                                    'error'
                            );
                            return false;
                    }
                }
            }

            /**
             * TO BE REMOVED -- BELOW
             */
            throw new Exception('You are seeing this because this error needs to be properly handled.',
                                0, $e);
            /**
             * TO BE REMOVED -- ABOVE
             */
            return false;
        }
    }

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
