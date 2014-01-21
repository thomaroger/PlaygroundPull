<?php

namespace PlaygroundPull\Mapper;

use Doctrine\ORM\EntityManager;
use ZfcBase\Mapper\AbstractDbMapper;

use PlaygroundPull\Options\ModuleOptions;

class Question
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $er;

    /**
     * @var \PlaygroundGallery\Options\ModuleOptions
     */
    protected $options;


    /**
    * __construct
    * @param Doctrine\ORM\EntityManager $em
    * @param PlaygroundGallery\Options\ModuleOptions $options
    *
    */
    public function __construct(EntityManager $em, ModuleOptions $options)
    {
        $this->em      = $em;
        $this->options = $options;
    }

    /**
    * findById : recupere l'entite en fonction de son id
    * @param int $id id de la company
    *
    * @return PlaygroundGallery\Entity\Category $category
    */
    public function findById($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $galleries collection de Citoren\Entity\Category
    */
    public function findBy($array)
    {
        return $this->getEntityRepository()->findBy($array);
    }


    public function findCurrentPull()
    {
        $currentDate = new \DateTime('NOW');
        $select  = " SELECT q.id ";
        $from    = " FROM PlaygroundPull\Entity\Question q";
        $where   = " WHERE (q.beginDate <='" . $currentDate->format('Y-m-d') . "' AND q.endedDate >= '" . $currentDate->format('Y-m-d') . "')
                    AND q.active = 1  ";
        $orderBy = " ORDER BY q.updated_at DESC";
        
        
        $query = $select.' '.$from.' '.$where.' '.$orderBy;

        $questions =  $this->em->createQuery($query)->getResult();
        
        if(count($questions) == 0) {
            return false;
        }

        return $this->findById($questions[0]['id']);
    }

    /**
    * insert : insert en base une entité category
    * @param PlaygroundGallery\Entity\Category $category category
    *
    * @return PlaygroundGallery\Entity\Category $category
    */
    public function insert($entity)
    {
        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité category
    * @param PlaygroundGallery\Entity\Category $category category
    *
    * @return PlaygroundGallery\Entity\Category $category
    */
    public function update($entity)
    {
        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité company et persiste en base
    * @param PlaygroundGallery\Entity\Category $entity category
    *
    * @return PlaygroundGallery\Entity\Category $category
    */
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
    * findAll : recupere toutes les entites
    *
    * @return collection $category collection de PlaygroundGallery\Entity\Category
    */
    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

     /**
    * remove : supprimer une entite category
    * @param PlaygroundGallery\Entity\Category $category Category
    *
    */
    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
    * getEntityRepository : recupere l'entite category
    *
    * @return PlaygroundGallery\Entity\Category $category
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundPull\Entity\Question');
        }

        return $this->er;
    }
}