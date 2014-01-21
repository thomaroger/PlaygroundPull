<?php

namespace PlaygroundPull\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\ServiceManager;

class Question extends ProvidesEventsForm
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
            'name' => 'question',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $translator->translate('Question', 'playgroundPull'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Question', 'playgroundPull'),
                'class' => 'form-control',
            ),
            'validator' => array(
                array('name' => 'Zend\Validator\NotEmpty'),
            )
        ));


        $this->add(array(
            'name' => 'beginDate',
            'type' => 'Zend\Form\Element\Date',
            'options' => array(
                'label' => $translator->translate('begin date', 'playgroundPull'),
                'format' => 'm/d/Y',
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('begin date', 'playgroundPull').' (mm/dd/yyyy)',
                'class' => 'form-control date',
            ),
        ));

        $this->add(array(
            'name' => 'endedDate',
            'type' => 'Zend\Form\Element\Date',
            'options' => array(
                'label' => $translator->translate('ended date', 'playgroundPull'),
                'format' => 'm/d/Y',
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('ended date', 'playgroundPull') .' (mm/dd/yyyy)',
                'class' => 'form-control date',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'active',
            'options' => array(
                'label' => $translator->translate('Status', 'playgroundPull'),
                'value_options' => $this->getQuestionStatuses(),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
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


    public function getQuestionStatuses()
    {
        return $this->getQuestionService()->getStatuses();
    }

    /**
      * Retrieve service question instance
     *
     * @return QuestionService
     */
    public function getQuestionService()
    {
        if (null === $this->questionService) {
            $this->questionService = $this->getServiceManager()->get('playgroundpull_question_service');
        }

        return $this->questionService;
    }

    /**
     * Set service question instance
     *
     * @param  QuestionService $questionService
     * @return Question
     */
    public function setQuestionService($questionService)
    {
        $this->questionService = $questionService;

        return $this;
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
     * @return Question
     */
    public function setServiceManager (ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

}
