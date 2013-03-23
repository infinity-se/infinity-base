<?php

namespace InfinityBase;

use InfinityBase\Mvc\View\Http\XhrListener;
use InfinityBase\View\Helper\Messages as MessagesHelper;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\ServiceManager\ServiceManager;

class Module implements AutoloaderProviderInterface, BootstrapListenerInterface, ConfigProviderInterface, ViewHelperProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function onBootstrap(EventInterface $event)
    {
        // Load variables
        $application = $event->getApplication();

        // Attach xhr listener
        $xhrListener = new XhrListener();
        $application->getEventManager()->attachAggregate($xhrListener);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'messages' => function(ServiceManager $serviceManager) {

                    // Load flash messenger
                    $flashmessenger = $serviceManager->getServiceLocator()
                            ->get('ControllerPluginManager')
                            ->get('flashMessenger');

                    // Load helper
                    $helper = new MessagesHelper();
                    $helper->setFlashMessenger($flashmessenger);

                    return $helper;
                }
            ),
        );
    }

}