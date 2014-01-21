<?php

namespace PlaygroundPull\Service;

use PlaygroundPull\Entity\Question as QuestionEntity;


use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Question implements ServiceManagerAwareInterface
{

    const PULL_INACTIVE = 0;
    const PULL_ACTIVE = 1;

    public static $statuses = array(
        self::PULL_INACTIVE => 'inactive',
        self::PULL_ACTIVE => 'active');

    /**
     * @var contactMapper
     */
    protected $questionMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var UserServiceOptionsInterface
     */
    protected $options;

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
   
    public function getStatuses()
    {
        return self::$statuses;
    }

    /**
     * getContactMapper
     *
     * @return ContactMapper
     */
    public function getQuestionMapper()
    {
        if (null === $this->questionMapper) {
            $this->questionMapper = $this->getServiceManager()->get('playgroundpull_question_mapper');
        }

        return $this->questionMapper;
    }

    /**
     * setCompanyMapper
     * @param  ContactMapper $companyMapper
     *
     * @return Citroen\Entity\Contact Contact
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