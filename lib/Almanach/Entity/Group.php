<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Ministrants entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="Almanach_Group")
 */
class Almanach_Entity_Group extends Zikula_EntityAccess
{

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $gid;

    /**
     * The following are annotations which define the name field.
     *
     * @ORM\Column(type="string", length="1000")
     */
    private $name;
    
    /**
     * The following are annotations which define the color field.
     *
     * @ORM\Column(type="string", length="7", nullable=true)
     */
    private $color;
    
    public function getGid()
    {
        return $this->gid;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
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
