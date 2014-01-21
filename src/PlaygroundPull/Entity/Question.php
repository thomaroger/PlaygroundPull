<?php

namespace PlaygroundPull\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="pull_question")
 */
class Question implements InputFilterAwareInterface
{

    protected $inputFilter;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * title
     * @ORM\Column(type="string", nullable=false)
     */
    protected $question;

    /**
     * active
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active = 0;

    /**
     * begin_date
     * @ORM\Column(name="begin_date", type="date", nullable=false)
     */
    protected $beginDate;

    /**
     * begin_date
     * @ORM\Column(name="ended_date", type="date", nullable=false)
     */
    protected $endedDate;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="PlaygroundPull\Entity\Answer", mappedBy="question")
     */
    protected $answers;


    public function __construct()
    {
        $this->websites = new ArrayCollection();
    }

    /**
     * @param int $id
     * @return Headline
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    } 

    /**
     * @param string $title
     * @return Headline
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return string $title
     */
    public function getQuestion()
    {
        return $this->question;
    }  

    /**
     * @param boolean $active
     * @return Headline
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }
    

    /**
     * @return boolean $active
     */
    public function getActive()
    {
        return $this->active;
    }  

     /**
     * @return datetime $beginDate
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * @param datetime $beginDate
     */
    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;

        return $this;
    }

     /**
     * @return datetime $beginDate
     */
    public function getEndedDate()
    {
        return $this->endedDate;
    }

    /**
     * @param datetime $beginDate
     */
    public function setEndedDate($endedDate)
    {
        $this->endedDate = $endedDate;

        return $this;
    }


     /** @PrePersist */
    public function createChrono()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /** @PreUpdate */
    public function updateChrono()
    {
        $this->updated_at = new \DateTime("now");
    }


    /**
     * @return the unknown_type
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param unknown_type $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function isCurrent()
    {
        if ($this->getActive() == false) {
            return false;
        }
        $currentTime = time();

        if(!($this->getBeginDate()->getTimestamp() <= $currentTime && $this->getEndedDate()->getTimestamp() > $currentTime)) {
            return false;
        }
        
        return true;
    }

    /**
     * @param unknown_type $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }


    /**
     * @param string $medias
     * @return Category
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }

    public function addAnswer($answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * @return string $media
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    public function removeAnswers(){
        $this->answers = new ArrayCollection();

        return $this;
    }

    public function getArrayCopy ()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
       
        if (isset($data['question']) && $data['question'] != null) {
            $this->question = $data['question'];
        }
        
        if (isset($data['active']) && $data['active'] != null) {
            $this->active = $data['active'];
        }

        if (isset($data['beginDate']) && $data['beginDate'] != null) {
            $this->beginDate = $data['beginDate'];
        }

        if (isset($data['endedDate']) && $data['endedDate'] != null) {
            $this->endedDate = $data['endedDate'];
        }
    }



    /**
    * setInputFilter
    * @param InputFilterInterface $inputFilter
    */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
    * getInputFilter
    *
    * @return  InputFilter $inputFilter
    */
    public function getInputFilter()
    {
         if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}