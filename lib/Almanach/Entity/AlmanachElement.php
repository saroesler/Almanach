<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Ministrants entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="Almanach_AlmanachElement")
 */
class Almanach_Entity_AlmanachElement extends Zikula_EntityAccess
{

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $eid;
    
    /**
     * The following are annotations which define the date field.
     *
     * @ORM\Column(type="integer")
     */
    private $did;
    
    /**
     * The following are annotations which define the almanac field.
     *
     * @ORM\Column(type="integer")
     */
    private $aid;
    
    /**
     * The following are annotations which define the color field.
     *
     * @ORM\Column(type="string", length="7", nullable=true)
     */
    private $color;
    
    public function getEidid()
    {
        return $this->eid;
    }
    
    public function getDid()
    {
        return $this->did;
    }

    public function setDid($did)
    {
        $this->did = $did;
    }
    
    public function getAid()
    {
        return $this->aid;
    }

    public function setAid($aid)
    {
        $this->aid = $aid;
    }
    
    public function getAlmanachName()
    {
    	return ModUtil::apiFunc('Almanach', 'Admin', 'getAlmanachName', array('aid' => $this->aid));
    }
    
    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }
}
