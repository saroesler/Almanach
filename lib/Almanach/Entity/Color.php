<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Ministrants entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="Almanach_Color")
 */
class Almanach_Entity_Color extends Zikula_EntityAccess
{

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cid;

    /**
     * The following are annotations which define the group id field.
     *
     * @ORM\Column(type="integer")
     */
    private $gid;
    
    /**
     * The following are annotations which define the adress field.
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
    
    public function getCid()
    {
        return $this->cid;
    }
    
    public function getGid()
    {
        return $this->gid;
    }
    
    public function setGid($gid)
    {
        $this->gid = $gid;
    }
    
    public function getGroupName()
    {
    	return ModUtil::apiFunc('Almanach', 'Admin', 'getGroupName', array('gid' => $this->gid));
    }
    
    public function getAid()
    {
        return $this->aid;
    }
    
    public function setAid($aid)
    {
         $this->aid = $aid;
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
