<?php

namespace InfinityBase\Mapper;

use Doctrine\ORM\EntityRepository;
use InfinityBase\ServiceManager\AbstractServiceLocatorAware;

abstract class AbstractMapper extends AbstractServiceLocatorAware
{

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * Get the accounts repository
     * 
     * @return EntityRepository
     */
    protected function getRepository()
    {
        if (null === $this->repository) {
            $this->repository = $this->getEntityManager()
                    ->getRepository($this->getModuleNamespace() . '\Entity\\' . $this->getEntityName());
        }
        return $this->repository;
    }

}