<?php

namespace InfinityBase\Service;

use InfinityBase\Service\AbstractServiceTrait;
use InfinityBase\ServiceManager\AbstractServiceLocatorAware;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

abstract class AbstractService extends AbstractServiceLocatorAware
{

    use AbstractServiceTrait;

    /**
     * @var mixed
     */
    private $identity;

    /**
     * @var string
     */
    protected $abstractType = 'service';

    /**
     * PROTECTED METHODS
     */

    /**
     * Get the current identity
     *
     * @return User
     */
    protected function identity()
    {
        // Get authentication service
        if (null === $this->identity) {
            $authenticationService = $this->getServiceLocator()
                    ->get('Zend\Authentication\AuthenticationService');
            if (!$authenticationService->hasIdentity()) {
                throw new \Exception('No identity loaded');
            }
            $this->identity = $authenticationService->getIdentity();
        }

        return $this->identity;
    }

    /**
     * Flush the entityManager and handle errors
     *
     * @return boolean
     */
    protected function save($successMessage, $failureMessage)
    {
        try {
            $this->getEntityManager()->flush();
            $this->addMessage($successMessage, FlashMessenger::NAMESPACE_SUCCESS);
            return true;
        } catch (\Exception $e) {
            $this->addMessage($failureMessage, FlashMessenger::NAMESPACE_ERROR);

            // Check exception type
            if ($e instanceof \Doctrine\DBAL\DBALException) {
                $previous = $e->getPrevious();
                if ($previous instanceof \Doctrine\DBAL\Driver\Mysqli\MysqliException) {

                    // Check exception type
                    switch ($previous->getCode()) {
                        case 1062:
                            $this->addMessage(
                                    'You are using details that already exist.', 'error'
                            );
                            return false;
                    }
                }
            }

            /**
             * TO BE REMOVED -- BELOW
             */
            throw new \Exception('You are seeing this because this error needs to be properly handled.', 0, $e);
            /**
             * TO BE REMOVED -- ABOVE
             */
            return false;
        }
    }

}
