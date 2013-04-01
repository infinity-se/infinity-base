<?php

namespace InfinityBase\ServiceManager;

use InfinityBase\EntityManager\EntityManagerAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class AbstractServiceLocatorAware implements ServiceLocatorAwareInterface
{
    use EntityManagerAwareTrait;

    /**
     * @var string
     */
    protected $abstractType;
    
    /**
     * @var string
     */
    private $moduleNamespace;
    
    /**
     * @var string
     */
    private $entityName;
    
    /**
     * Retrieve the module namespace
     * 
     * @return string
     */
    protected function getModuleNamespace()
    {
        if (null === $this->moduleNamespace) {
            $namespace = get_class($this);
            $this->moduleNamespace = substr($namespace, 0, strpos($namespace, '\\'));
        }
        return $this->moduleNamespace;
    }
    
    /**
     * Retrieve the entity name
     * 
     * @return string
     */
    protected function getEntityName()
    {
        if (null === $this->entityName) {
            $name = get_class($this);
            $type = ucfirst($this->abstractType);
            $name = str_replace($this->getModuleNamespace() . '\\' . $type . '\\', '', $name);
            $name = str_replace($type, '', $name);
            $this->entityName = $name;
        }
        return $this->entityName;
    }
}