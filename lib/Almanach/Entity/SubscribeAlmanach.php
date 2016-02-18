<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Ministrants entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="Almanach_SubscribeAlmanach")
 */
class Almanach_Entity_SubscribeAlmanach extends Zikula_EntityAccess
{

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $said;
    
    /**
     * The following are annotations which define the user id field.
     *
     * @ORM\Column(type="integer")
     */
    private $uid;
    
    /**
     * The following are annotations which define the almanach id field.
     *
     * @ORM\Column(type="integer")
     */
    private $aid;

    
    public function getTfid()
    {
        return $this->tfid;
    }
    
    public function getTid()
    {
        return $this->tid;
    }

    public function setTid($tid)
    {
        $this->tid = $tid;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
