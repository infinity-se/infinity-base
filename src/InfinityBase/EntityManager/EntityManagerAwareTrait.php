<?php

namespace InfinityBase\EntityManager;

use Doctrine\ORM\EntityManager;
use InfinityBase\ServiceManager\ServiceLocatorAwareTrait;

trait EntityManagerAwareTrait
{

    use ServiceLocatorAwareTrait;

    /**
     * @var EntityManager
     */
    private $_entityManager;

    /**
     * Get entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (!isset($this->_entityManager)) {
            $this->_entityManager = $this->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');
        }
        return $this->_entityManager;
    }

}

