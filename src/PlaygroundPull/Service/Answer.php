<?php
namespace PlaygroundPull\Service;

use PlaygroundPull\Entity\Answer as AnswerEntity;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Answer implements ServiceManagerAwareInterface
{

    /**
     * @var answerMapper
     */
    protected $answerMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
    * create 
    * @param array $data 
    * 
    * @return Entity/Answer $answer 
    */
    public function create($data)
    {
        
        $answer = new AnswerEntity();
        $data['count'] = 0;
        $answer->populate($data);

        $form = $this->getServiceManager()->get('playgroundpull_answer_form');
        $form->bind($answer);
        $form->setData($data);

        
        if (!$form->isValid()) {
            return false;
        }

        $question = $this->getServiceManager()->get('playgroundpull_question_mapper')->findById($data['questionId']);
        $answer->setQuestion($question);

        $answer = $this->getAnswerMapper()->insert($answer);

        return $answer;
    }

    /**
     * getAnswerMapper
     *
     * @return answerMapper
     */
    public function getAnswerMapper()
    {
        if (null === $this->answerMapper) {
            $this->answerMapper = $this->getServiceManager()->get('playgroundpull_answer_mapper');
        }

        return $this->answerMapper;
    }

    /**
     * setAnswerMapper
     * @param  AnswerMapper $answerMapper
     *
     * @return Service/Answer
     */
    public function setAnswerMapper($answerMapper)
    {
        $this->answerMapper = $answerMapper;

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