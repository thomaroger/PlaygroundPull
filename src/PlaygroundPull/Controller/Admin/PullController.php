<?php


namespace PlaygroundPull\Controller\Admin;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class PullController extends AbstractActionController
{
    
    protected $questionService;
    protected $answerService;
    protected $questionForm;
    protected $formAnswer;

    public function listAction()
    {        
        
        $pulls = $this->getQuestionService()->getQuestionMapper()->findAll();
        $viewModel = new ViewModel();

        return $viewModel->setVariables(array('pulls' => $pulls));
    }

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

    public function removeAction()
    {
        $currentDate = new \DateTime('NOW');

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



    public function getQuestionService()
    {
        if (null === $this->questionService) {
            $this->questionService = $this->getServiceLocator()->get('playgroundpull_question_service');
        }

        return $this->questionService;
    }

    public function getAnswerService()
    {
        if (null === $this->answerService) {
            $this->answerService = $this->getServiceLocator()->get('playgroundpull_answer_service');
        }

        return $this->answerService;
    }

    public function getQuestionForm()
    {
        if($this->questionForm === null){
            $this->questionForm = $this->getServiceLocator()->get('playgroundpull_question_form');
        }

        return $this->questionForm;
    }

    public function getAnswerForm()
    {
        if($this->formAnswer === null){
            $this->formAnswer = $this->getServiceLocator()->get('playgroundpull_answer_form');
        }
        return $this->formAnswer;
  
    }
  
}