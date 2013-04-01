<?php

namespace InfinityBase\Mapper;

use Doctrine\ORM\EntityRepository;
use InfinityBase\ServiceManager\AbstractServiceLocatorAware;

abstract class AbstractMapper extends AbstractServiceLocatorAware
{

    use EntityManagerAwareTrait;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * Get the accounts repository
     * 
     * @return EntityRepository
     */
    private function getRepository()
    {
        if (null === $this->repository) {
            $this->repository = $this->getEntityManager()
                    ->getRepository($this->getModuleNamespace() . '\Entity\\' . $this->getEntityName());
        }
        return $this->repository;
    }

}