<?php


namespace PlaygroundPull\Controller\Frontend;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class PullController extends AbstractActionController
{
    /**
    * @var $answerService : Service des reponse
    */
    protected $answerService;

    /**
    * postAction : permet de poster une reponse pour un sondage
    *
    * @return Reponse
    */
    public function postAction()
    {        
        $return = array();
        $response = $this->getResponse();
        $response->setStatusCode(200);

        $answerId = $this->getEvent()->getRouteMatch()->getParam('answerId');
        $answer = $this->getAnswerService()->getAnswerMapper()->findById($answerId); 
        if (empty($answer)) {
            $return['status'] = 1;
            $return['message'] = "invalid argument : answer invalid";
            $response->setContent(json_encode($return));
        
            return $response;
        }
        $answer->setCount($answer->getCount()+1);
        $answer = $this->getAnswerService()->getAnswerMapper()->update($answer); 
        
        $return['status'] = 0;
        $return['message'] = "";
        $response->setContent(json_encode($return));
        return $response;
    }

    /**
    * getAnswerService : permet de recuperer le service de reponse
    *
    * @return answerService
    */
    public function getAnswerService()
    {
        if (null === $this->answerService) {
            $this->answerService = $this->getServiceLocator()->get('playgroundpull_answer_service');
        }

        return $this->answerService;
    }
}