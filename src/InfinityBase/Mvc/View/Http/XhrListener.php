<?php

namespace InfinityBase\Mvc\View\Http;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventInterface;
use Zend\View\Model\ViewModel;

class XhrListener implements ListenerAggregateInterface
{

    /**
     * @var array
     */
    protected $_listeners = array();

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $eventManager)
    {
        $this->_listeners[] = $eventManager->attach(
                'dispatch', array($this, 'check'), -100
        );
    }

    /**
     * {@inheritDoc}
     */
    public function detach(EventManagerInterface $eventManager)
    {
        foreach ($this->_listeners as $index => $listener) {
            if ($eventManager->detach($listener)) {
                unset($this->_listeners[$index]);
            }
        }
    }

    /**
     * Check for XmlHttpRequest
     *
     * @param EventInterface $event
     */
    public function check(EventInterface $event)
    {
        // Check result
        $result = $event->getResult();
        if (!$result instanceof ViewModel) {
            return;
        }

        // Check for xhr request
        $request = $event->getRequest();
        if ($request->isXmlHttpRequest()) {

            // Set terminal
            $result->setTerminal(true);
            $event->setViewModel($result);
        }
    }

}

