<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Ministrants entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="Almanach_Almanach")
 */
class Almanach_Entity_Almanach extends Zikula_EntityAccess
{

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $aid;
    
    /**
     * The following are annotations which define the name field.
     *
     * @ORM\Column(type="string", length="200")
     */
    private $name;

    /**
     * The following are annotations which define the overlap field.
     *
     * @ORM\Column(type="integer")
     */
    private $overlapping;
    
    /**
     * The following are annotations which define the input field.
     *
     * @ORM\Column(type="integer")
     */
    private $input;
    
    /**
     * The following are annotations which define the template field.
     *
     * @ORM\Column(type="string", length="2000", nullable=true)
     */
    private $template;
    
    //no database field
    private $color;
    
    public function getAid()
    {
        return $this->aid;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getOverlapping()
    {
        return $this->overlapping;
    }

    public function setOverlapping($overlapping)
    {
        $this->overlapping = $overlapping;
    }
    
    public function getInput()
    {
        return $this->input;
    }

    public function setInput($input)
    {
        $this->input = $input;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
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
