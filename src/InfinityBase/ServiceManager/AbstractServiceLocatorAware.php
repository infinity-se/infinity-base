<?php

namespace InfinityBase\ServiceManager;

use InfinityBase\Entity\EntityAwareTrait;
use InfinityBase\EntityManager\EntityManagerAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class AbstractServiceLocatorAware implements ServiceLocatorAwareInterface
{
    /**s
     * @var string
     */
    protected $abstractType;

    use EntityAwareTrait;
    use EntityManagerAwareTrait;
}