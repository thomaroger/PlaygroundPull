<?php

namespace PlaygroundPull\Service;

use PlaygroundPull\Entity\Question as QuestionEntity;


use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Question implements ServiceManagerAwareInterface
{

    /**
     * @var PULL_INACTIVE
     */
    const PULL_INACTIVE = 0;
    
    /**
     * @var PULL_ACTIVE
     */
    
    const PULL_ACTIVE = 1;
    /**
    * @var $statuses : Tableau de statut
    */
    public static $statuses = array(
        self::PULL_INACTIVE => 'inactive',
        self::PULL_ACTIVE => 'active');

    /**
     * @var questionMapper
     */
    protected $questionMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
    * create 
    * @param array $data 
    * 
    * @return Entity/Question $question 
    */
    public function create($data)
    {
        $question = new QuestionEntity();

        if(!empty($data['beginDate'])) {
            $beginDate = \DateTime::createFromFormat('m/d/Y', $data['beginDate']);
            $data['beginDate'] = $beginDate;
        }

        if(!empty($data['endedDate'])) {
            $endedDate = \DateTime::createFromFormat('m/d/Y', $data['endedDate']);
            $data['endedDate'] = $endedDate;
        }

        $question->populate($data);
        $form = $this->getServiceManager()->get('playgroundpull_question_form');
        $form->bind($question);
        $form->setData($data);

        
        if (!$form->isValid()) {
            return false;
        }

        $question = $this->getQuestionMapper()->insert($question);

        return $question;
    }

    /**
    * update 
    * @param Entity/Question $question 
    * @param array $data 
    * 
    * @return Entity/Question $question 
    */
    public function update($question, $data)
    {

        if(!empty($data['beginDate'])) {
            $beginDate = \DateTime::createFromFormat('m/d/Y', $data['beginDate']);
            $data['beginDate'] = $beginDate;
        }

        if(!empty($data['endedDate'])) {
            $endedDate = \DateTime::createFromFormat('m/d/Y', $data['endedDate']);
            $data['endedDate'] = $endedDate;
        }

        $question->populate($data);
        $form = $this->getServiceManager()->get('playgroundpull_question_form');
        $form->bind($question);
        $form->setData($data);

        
        if (!$form->isValid()) {
            return false;
        }

        $question = $this->getQuestionMapper()->update($question);

        return $question;
    }
    
    /**
    * getStatuses 
    * 
    * @return Array $statuses 
    */
    public function getStatuses()
    {
        return self::$statuses;
    }

    /**
     * getQuestionMapper
     *
     * @return questionMapper
     */
    public function getQuestionMapper()
    {
        if (null === $this->questionMapper) {
            $this->questionMapper = $this->getServiceManager()->get('playgroundpull_question_mapper');
        }

        return $this->questionMapper;
    }

    /**
     * setQuestionMapper
     * @param  QuestionMapper $questionMapper
     *
     * @return Service/Question
     */
    public function setQuestionMapper($questionMapper)
    {
        $this->questionMapper = $questionMapper;

        return $this;
    }

   

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}