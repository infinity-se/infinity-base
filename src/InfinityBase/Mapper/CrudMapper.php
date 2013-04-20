<?php

namespace InfinityBase\Mapper;

class CrudMapper extends AbstractMapper
{

    /**
     * Find entity by id
     *
     * @param int $id
     * @return object
     */
    public function findById($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * Find entities
     *
     * @return array
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

}

