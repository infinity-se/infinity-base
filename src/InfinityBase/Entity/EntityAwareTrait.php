<?php

namespace InfinityBase\Entity;

trait EntityAwareTrait
{

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
            $class                 = get_class($this);
            $this->moduleNamespace = substr($class, 0, strpos($class, '\\'));
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
            $name             = get_class($this);
            $type             = ucfirst($this->abstractType);
            $name             = str_replace($this->getModuleNamespace() . '\\' . $type . '\\', '', $name);
            $name             = str_replace($type, '', $name);
            $this->entityName = $name;
        }
        return $this->entityName;
    }

}

