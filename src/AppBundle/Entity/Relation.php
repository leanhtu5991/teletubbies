<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
* @ORM\Entity
* @ORM\Table(name="relation")
*/
class Relation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     private $id;

    /**
     * @var \AppBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user1", referencedColumnName="id")
     */
     private $user1;
    /**
     * @var \AppBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user2", referencedColumnName="id")
     */
     private $user2;

    public function __construct()
    {
        $this->user1 = new ArrayCollection();
        $this->user2 = new ArrayCollection();
    }

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function getUser1(){
        return $this->user1;
    }
    public function setUser1(ArrayCollection $user1){
        $this->user1 = $user1;
    }
    public function getUser2(){
        return $this->user2;
    }
    public function setUser2(ArrayCollection $user2){
        $this->user2 = $user2;
    }
}