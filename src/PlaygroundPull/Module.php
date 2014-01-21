<?php

namespace PlaygroundPull;

use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{
    protected $eventsArray = array();
    
    public function onBootstrap(MvcEvent $e)
    {
        $application     = $e->getTarget();
        $serviceManager  = $application->getServiceManager();
        $eventManager    = $application->getEventManager();

        $translator = $serviceManager->get('translator');

        // Gestion de la locale
        if (PHP_SAPI !== 'cli') {
            $locale = null;
            $options = $serviceManager->get('playgroundcore_module_options');

            $locale = $options->getLocale();

            $translator->setLocale($locale);

            // plugins
            $translate = $serviceManager->get('viewhelpermanager')->get('translate');
            $translate->getTranslator()->setLocale($locale);  
        }
        
        AbstractValidator::setDefaultTranslator($translator,'playgroundpull');
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'playgroundpull_doctrine_em' => 'doctrine.entitymanager.orm_default',
            ),
            'factories' => array(
                'playgroundpull_module_options' => function  ($sm) {
                    $config = $sm->get('Configuration');
                    
                    return new Options\ModuleOptions(isset($config['playgroundpull']) ? $config['playgroundpull'] : array());
                },
                'playgroundpull_question_mapper' => function  ($sm) {
                    return new Mapper\Question($sm->get('playgroundpull_doctrine_em'), $sm->get('playgroundpull_module_options'));
                },
                'playgroundpull_answer_mapper' => function  ($sm) {
                    return new Mapper\Answer($sm->get('playgroundpull_doctrine_em'), $sm->get('playgroundpull_module_options'));
                },
                'playgroundpull_question_form' => function($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Question(null, $sm, $translator);
                
                    return $form;
                },

                 'playgroundpull_answer_form' => function($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Answer(null, $sm, $translator);
                
                    return $form;
                },
            ),
            'invokables' => array(
                'playgroundpull_question_service' => 'PlaygroundPull\Service\Question',
                'playgroundpull_answer_service' => 'PlaygroundPull\Service\Answer',
            ),
        );
    }
}
