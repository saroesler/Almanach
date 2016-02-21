<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Ministrants entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="Almanach_SubscribeDate")
 */
class Almanach_Entity_SubscribeDate extends Zikula_EntityAccess
{

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $sdid;
    
    /**
     * The following are annotations which define the user id field.
     *
     * @ORM\Column(type="integer")
     */
    private $uid;
    
    /**
     * The following are annotations which define the date id field.
     *
     * @ORM\Column(type="integer")
     */
    private $did;

    
    public function getSdid()
    {
        return $this->sdid;
    }
    
    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }
    
    public function getDid()
    {
        return $this->did;
    }

    public function setDid($did)
    {
        $this->did = $did;
    }
}
