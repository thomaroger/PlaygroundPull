<?php

namespace PlaygroundPull\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\ServiceManager;

class Answer extends ProvidesEventsForm
{

    /**
    * @var $serviceManager : Service Manager
    */
    protected $serviceManager;
     /**
    * @var $questionService : Service de question
    */
    protected $questionService;

    public function __construct ($name = null, ServiceManager $sm, Translator $translator)
    {

        parent::__construct($name);
        $this->setServiceManager($sm);

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => 0
            )
        ));
        
        $this->add(array(
            'name' => 'answer',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $translator->translate('Answer', 'playgroundPull'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Answer', 'playgroundPull'),
                'class' => 'form-control',
            ),
            'validator' => array(
                array('name' => 'Zend\Validator\NotEmpty'),
            )
        ));

        $submitElement = new Element\Button('submit');
        $submitElement->setAttributes(array(
            'type' => 'submit',
            'class'=> 'btn btn-success'
        ));

        $this->add($submitElement, array(
            //'priority' => - 100
        ));

    }


    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager ()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager (ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

}
