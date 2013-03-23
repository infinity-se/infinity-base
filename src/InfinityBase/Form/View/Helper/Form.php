<?php

namespace InfinityBase\Form\View\Helper;

use Zend\Form\FormInterface;
use Zend\Form\View\Helper\Form as FormHelper;

/**
 * View helper plugin to prepare and render the entire form
 */
class Form extends FormHelper
{

    /**
     * Render the entire form
     *
     * @param FormInterface $form
     * @return Form|string
     */
    public function __invoke(FormInterface $form = null)
    {
        if (!isset($form)) {
            return $this;
        }

        // Set up form
        $form->prepare();
        $view = $this->getView();

        // Start form output
        $output = $this->openTag($form);

        // Loop through elements
        foreach ($form->getElements() as $element) {
            // Output element
            $output .= $view->formRow($element) . '<br>';
        }

        // End form output
        $output .= $this->closeTag();

        return $output;
    }

}