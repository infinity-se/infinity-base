<?php

namespace InfinityBase\Controller;

use Zend\Http\Response;
use Zend\View\Model\ViewModel;

abstract class CrudActionController extends AbstractActionController
{

    /**
     * @var string
     */
    protected $indexRoute = '';

    /**
     * Create entity
     *
     * @return ViewModel|Response
     */
    public function createAction()
    {
        // Load form
        $entityClass = $this->getModuleNamespace() . '\Entity\\' . $this->getEntityName();
        $entity      = new $entityClass();
        $form        = $this->getForm('Create' . $this->getEntityName());
        $form->bind($entity);

        // Implement Post/Redirect/Get pattern
        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        } else if (false !== $prg) {

            // Set form data
            $form->setData($prg);

            // Create account
            $result = $this->getService()->create();
            if ($result) {
                return $this->redirect()->toRoute($this->indexRoute);
            }
        }

        // Set up view model
        $viewModel = new ViewModel(array(
            'entityName' => $this->getEntityName(),
            'form'       => $form,
        ));
        $viewModel->setTemplate('infinity-base/crud/create');

        return $viewModel;
    }

    /**
     * Edit entity
     *
     * @return ViewModel|Response
     */
    public function editAction()
    {
        // Load entity
        $entity = $this->getMapper()->findById($this->params('id'));
        if (!$entity) {
            // Add failure message
            $this->flashMessenger()
                    ->addMessage(
                            'That ' . strtolower($this->getEntityName())
                            . ' does not exist.'
            );
            return $this->redirect()->toRoute($this->indexRoute);
        }

        // Load form
        $form = $this->getForm('Edit' . $this->getEntityName());
        $form->bind($entity);

        // Implement Post/Redirect/Get pattern
        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        } else if (false !== $prg) {

            // Set form data
            $form->setData($prg);

            // Edit entity
            $result = $this->getService()->edit();
            if ($result) {
                return $this->redirect()->toRoute($this->indexRoute);
            }
        }

        // Set up view model
        $viewModel = new ViewModel(array(
            'entityName' => $this->getEntityName(),
            'form'       => $form,
        ));
        $viewModel->setTemplate('infinity-base/crud/edit');

        return $viewModel;
    }

    /**
     * Delete entity
     *
     * @return ViewModel|Response
     */
    public function deleteAction()
    {
        // Load entity
        $entity = $this->getMapper()->findById($this->params('id'));
        if (!$entity) {
            // Add failure message
            $this->flashMessenger()
                    ->addMessage(
                            'That ' . strtolower($this->getEntityName())
                            . ' does not exist.'
            );
            return $this->redirect()->toRoute($this->indexRoute);
        }

        // Load form
        $form = $this->getForm('DeleteAccount');
        $form->bind($entity);

        // Implement Post/Redirect/Get pattern
        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        } else if (false !== $prg) {

            // Set form data
            $form->setData($prg);

            // Delete entity
            $result = $this->getService()->delete();
            if ($result) {
                return $this->redirect()->toRoute($this->indexRoute);
            }
        }

        // Set up view model
        $viewModel = new ViewModel(array(
            'entityName' => $this->getEntityName(),
            'form'       => $form,
        ));
        $viewModel->setTemplate('infinity-base/crud/delete');

        return $viewModel;
    }

}
