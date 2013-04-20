<?php

namespace InfinityBase\Service;

use Zend\Mvc\Controller\Plugin\FlashMessenger;

abstract class CrudService extends AbstractService
{

    /**
     * Create entity
     *
     * @return boolean
     */
    public function create()
    {
        // Validate form
        $form = $this->getForm('Create' . $this->getEntityName());
        if (!$form->isValid()) {
            return false;
        }

        // Insert new entity
        $now    = new \DateTime();
        $entity = $form->getData();
        $entity->setLastModified($now);
        $entity->setCreated($now);

        // Trigger oncreate
        $this->onCreate($entity);

        // Save
        $this->getEntityManager()->persist($entity);
        return $this->save($this->getEntityName() . ' created.', 'Unable to create ' . strtolower($this->getEntityName()) . '.');
    }

    /**
     * Edit entity
     *
     * @return boolean
     */
    public function edit()
    {
        // Validate form
        $form = $this->getForm('Edit' . $this->getEntityName());
        if (!$form->isValid()) {
            return false;
        }

        // Update account
        $now    = new \DateTime();
        $entity = $form->getData();
        $entity->setLastModified($now);

        // Trigger onedit
        $this->onEdit($entity);

        // Save
        return $this->save($this->getEntityName() . ' saved.', 'Unable to save ' . strtolower($this->getEntityName()) . '.');
    }

    /**
     * Delete entity
     *
     * @return boolean
     */
    public function delete()
    {
        // Validate form
        $form = $this->getForm('Delete' . $this->getEntityName());
        if (!$form->isValid()) {
            $this->addMessage($form->getMessages(), FlashMessenger::NAMESPACE_ERROR);
            return false;
        }

        // Remove account entity
        $entity = $form->getData();
        $this->getEntityManager()->remove($entity);

        // Trigger ondelete
        $this->onDelete($entity);

        // Save
        return $this->save($this->getEntityName() . ' deleted.', 'Unable to delete ' . strtolower($this->getEntityName()) . '.');
    }

    public function onCreate($entity)
    {

    }

    public function onEdit($entity)
    {

    }

    public function onDelete($entity)
    {

    }

}
