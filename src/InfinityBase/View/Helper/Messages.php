<?php

namespace InfinityBase\View\Helper;

use Zend\Mvc\Controller\Plugin\FlashMessenger as FlashMessengerPlugin;
use Zend\View\Helper\AbstractHelper;

class Messages extends AbstractHelper
{

    /**
     * @var FlashMessengerPlugin
     */
    protected $_flashMessenger;

    /**
     * Render flashMessenger messages
     *
     * @param string $namespace
     * @return FlashMessengerPlugin
     */
    public function __invoke($namespace = 'default')
    {
        // Start output
        $output = '';

        // Set namespace
        $this->_flashMessenger->setNamespace($namespace);

        // Check for messages
        if ($this->_flashMessenger->hasMessages()) {
            $output .= $this->_renderMessages($this->_flashMessenger->getMessages());
            $this->_flashMessenger->clearMessages();
        }

        // Check for current messages
        if ($this->_flashMessenger->hasCurrentMessages()) {
            $output .= $this->_renderMessages($this->_flashMessenger->getCurrentMessages());
            $this->_flashMessenger->clearCurrentMessages();
        }

        return $output;
    }

    /**
     * Set the flashMessenger controller plugin
     *
     * @param FlashMessengerPlugin $flashMessenger
     */
    public function setFlashMessenger(FlashMessengerPlugin $flashMessenger)
    {
        $this->_flashMessenger = $flashMessenger;
    }

    /**
     * Render message array
     *
     * @param array $messages
     */
    protected function _renderMessages(array $messages)
    {
        // Don't create list for single message
        if (count($messages) == 1) {
            return reset($messages);
        }

        // Start output
        $output = '<ul>';

        // Loop through messages
        foreach ($messages as $message) {

            // Add message
            $output .= '<li>' . $message . '</li>';
        }

        // Finish output
        $output .= '</ul>';

        return $output;
    }

}

