<?php
namespace PlaygroundPull\Controller\Admin;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class PullController extends AbstractActionController
{
    /**
    * @var $questionService : Service des questions
    */
    protected $questionService;
    /**
    * @var $answerService : Service des reponses
    */
    protected $answerService;
    /**
    * @var $questionForm : Form des questions
    */
    protected $questionForm;
    /**
    * @var $formAnswer : Form des reponses
    */
    protected $formAnswer;

    /**
    * listAction : permet de lister les sondages en cours
    *
    * @return ViewModel
    */
    public function listAction()
    {        
        
        $pulls = $this->getQuestionService()->getQuestionMapper()->findAll();
        $viewModel = new ViewModel();

        return $viewModel->setVariables(array('pulls' => $pulls));
    }

    /**
    * addAction : permet d'ajouter un sondage
    *
    * @return ViewModel
    */
    public function addAction()
    {
        $form = $this->getQuestionForm();
        $request = $this->getRequest();
        $message = '';
        $state = '';
        
        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );  
            $question = $this->getQuestionService()->create($data);
            if ($question) {
                $this->flashMessenger()->addMessage(' alert-success');
                $this->flashMessenger()->addMessage('The question "'.$question->getQuestion().'" was created');

                return $this->redirect()->toRoute('admin/playgroundpull/edit', array('questionId' => $question->getId()));
            } else {
                $state = 'alert-danger';
                $message = 'The question was not created!';
            }
        }
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-pull/pull/pull');

        return $viewModel->setVariables(array('form' => $form,
                                              'flashMessages' => $this->flashMessenger()->getMessages()));
    }

    /**
    * editAction : permet d'editer un sondage
    *
    * @return ViewModel
    */
    public function editAction()
    {
        $questionId = $this->getEvent()->getRouteMatch()->getParam('questionId');
        $question = $this->getQuestionService()->getQuestionMapper()->findById($questionId); 
        if(empty($question)){

            return $this->redirect()->toRoute('admin/playgroundpull/add');
        }

        $form = $this->getQuestionForm();
        $formAnswer = $this->getAnswerForm();
        $request = $this->getRequest();

        $form->bind($question);       

        if ($request->isPost()) {
           
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );  
            if(!empty($data['beginDate'])) {
                $question = $this->getQuestionService()->update($question, $data);
                if ($question) {
                    $this->flashMessenger()->addMessage(' alert-success');
                    $this->flashMessenger()->addMessage('The question "'.$question->getQuestion().'" was edited');

                    return $this->redirect()->toRoute('admin/playgroundpull/edit', array('questionId' => $question->getId()));
                } else {
                    $state = 'alert-danger';
                    $message = 'The question was not edited!';
                }
            } else {
               
                    $answer = $this->getAnswerService()->create($data);
                if ($answer) {
                    $this->flashMessenger()->addMessage(' alert-success');
                    $this->flashMessenger()->addMessage('The anwser "'.$answer->getAnswer().'" was created');

                    return $this->redirect()->toRoute('admin/playgroundpull/edit', array('questionId' => $question->getId()));
                } else {
                    $state = 'alert-danger';
                    $message = 'The answer was not created!';
                }
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-pull/pull/pull');

        return $viewModel->setVariables(array('form'          => $form,
                                              'question'      => $question,
                                              'formAnswer'    => $formAnswer,
                                              'flashMessages' => $this->flashMessenger()->getMessages()));
    }

    /**
    * removeAction : permet de supprimer un sondage
    *
    * @return Response
    */
    public function removeAction()
    {

        $questionId = $this->getEvent()->getRouteMatch()->getParam('questionId');
        $question = $this->getQuestionService()->getQuestionMapper()->findById($questionId); 

        if(empty($question)){

            return $this->redirect()->toRoute('admin/playgroundpull');
        }
        
        if ($question->isCurrent()) {
            $this->flashMessenger()->addMessage(' alert-danger');
            $this->flashMessenger()->addMessage('The question "'.$question->getQuestion().'" was not deleted');    
    
            return $this->redirect()->toRoute('admin/playgroundpull');
        }

        $title = $question->getQuestion();
        foreach ($question->getAnswers() as $answer) {
            $this->getAnswerService()->getAnswerMapper()->remove($answer);
        }
        $this->getQuestionService()->getQuestionMapper()->remove($question);
        
        $this->flashMessenger()->addMessage(' alert-success');
        $this->flashMessenger()->addMessage('The question "'.$title.'" was deleted');    
        

        return $this->redirect()->toRoute('admin/playgroundpull');
    }


    /**
    * getQuestionService : permet de recuperer le service de questions
    *
    * @return questionService
    */
    public function getQuestionService()
    {
        if (null === $this->questionService) {
            $this->questionService = $this->getServiceLocator()->get('playgroundpull_question_service');
        }

        return $this->questionService;
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

    /**
    * getQuestionForm : permet de recuperer le form de question
    *
    * @return questionForm
    */
    public function getQuestionForm()
    {
        if($this->questionForm === null){
            $this->questionForm = $this->getServiceLocator()->get('playgroundpull_question_form');
        }

        return $this->questionForm;
    }

    /**
    * getAnswerForm : permet de recuperer le form de reponse
    *
    * @return formAnswer
    */
    public function getAnswerForm()
    {
        if($this->formAnswer === null){
            $this->formAnswer = $this->getServiceLocator()->get('playgroundpull_answer_form');
        }
        return $this->formAnswer;
  
    }
  
}