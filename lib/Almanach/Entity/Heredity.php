<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Ministrants entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="Almanach_Heredity")
 */
class Almanach_Entity_Heredity extends Zikula_EntityAccess
{

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $hid;

    /**
     * The following are annotations which define the parent almanach id field.
     *
     * @ORM\Column(type="integer")
     */
    private $paid;
    
    /**
     * The following are annotations which define the child amanach field.
     *
     * @ORM\Column(type="integer")
     */
    private $caid;
    
    /**
     * The following are annotations which define the color field.
     *
     * @ORM\Column(type="string", length="7", nullable=true)
     */
    private $color;
    
    public function getHid()
    {
        return $this->hid;
    }
    
    public function getPaid()
    {
        return $this->paid;
    }

    public function setPaid($paid)
    {
        $this->paid = $paid;
    }
    
    public function getCaid()
    {
        return $this->caid;
    }
    
    public function getCAlmanachName()
    {
    	return ModUtil::apiFunc('Almanach', 'Admin', 'getAlmanachName', array('aid' => $this->caid));
    }

    public function setCaid($caid)
    {
        $this->caid = $caid;
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
