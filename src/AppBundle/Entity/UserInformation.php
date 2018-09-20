<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
* @ORM\Entity
* @ORM\Table(name="user_information")
*/
class UserInformation
{
   /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

   /**
    * @ORM\Column(type="integer")
    */
    private $age;

   /**
    * @ORM\Column(type="string", length=50)
    */
    private $famille;

   /**
    * @ORM\Column(type="string", length=50)
    */
    private $race;

   /**
    * @ORM\Column(type="string", length=50)
    */
    private $nourriture;

        /**
     * @var \AppBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
     private $user;

   public function __construct()
   {
       $this->user = new ArrayCollection();
   }

   public function getId(){
    return $this->id;
    }   
    public function setId($id){
        $this->id = $id;
    }
    public function getAge(){
        return $this->age;
    }
    public function setAge($age){
        $this->age = $age;
    }
    public function getRace(){
        return $this->race;
    }
    public function setRace($race){
        $this->race = $race;
    }
    public function getFamille(){
        return $this->famille;
    }
    public function setFamille($famille){
        $this->famille = $famille;
    }
    public function getNourriture(){
        return $this->nourriture;
    }
    public function setNourriture($nourriture){
        $this->nourriture = $nourriture;
    }
    public function getUser(){
        return $this->user;
    }
    public function setUser(\AppBundle\Entity\User $user){
        $this->user = $user;
        return $this;
    }

}
