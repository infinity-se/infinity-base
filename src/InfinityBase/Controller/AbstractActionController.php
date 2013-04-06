<?php

namespace InfinityBase\Controller;

use InfinityBase\Service\AbstractServiceTrait;
use InfinityBase\Entity\EntityAwareTrait;
use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;

class AbstractActionController extends ZendAbstractActionController
{
    use AbstractServiceTrait;
    use EntityAwareTrait;
    
    /**
     * @var string
     */
    protected $abstractType = 'controller';
    
    /**
     * @var AbstractService
     */
    private $service;
    
    /**
     * Retrieve the service
     *
     * @return AbstractService
     */
    protected function getService()
    {
        if (!isset($this->service)) {
            $this->service = $this->getServiceLocator()
                    ->get($this->getModuleNamespace() . '\Service\\' . $this->getEntityName());
        }

        return $this->service;
    }

}