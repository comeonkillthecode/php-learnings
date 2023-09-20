<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(collection="Students")
 */
class Student implements DocumentInterface
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotNull
     */
    private $name;

    /**
     * @MongoDB\Field(type="string")
     */
    private $branch;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $age;
    
    
    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Get the value of id
     */ 
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }


    /**
     * Get the value of age
     */ 
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }
}