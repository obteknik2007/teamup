<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StaffRepository")
 */
class Staff
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    // add your own fields
      //Date de création enregistrement
    /**
    * @ORM\Column(type="datetime",nullable=false)
    * * @var \Datetime
    */
    private $createdAt;
    
    
    //Date de modification enregistrement
    /**
    * @ORM\Column(type="datetime",nullable=true)
    * * @var datetime
    */
    private $updatedAt;
    
    /** 
    * @var string
    * @ORM\Column(length=25)
    * @Assert\NotBlank()
    */
    private $nom;
    
    /** 
    * @var string
    * @ORM\Column(length=30)
    * @Assert\NotBlank()
    */
    private $prenom;
    
    /** 
    * @var string
    * @ORM\Column(length=30)
    * @Assert\NotBlank()
    */
    private $fonction;  
    
     /**
     * @ORM\ManyToOne(targetEntity="Club",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @var Club 
     */
     private $club;
    
     /**
     * @ORM\Column(nullable=true)
     * @Assert\Image()
     * @var string 
     */
     private $image;
     
    //CONSTRUCTEUR : initié pour l'enregistrement automatique de la date 
    //de création de l'enregistrement
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
    
    //GETTERS ET SETTERS
    public function getId() {
        return $this->id;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getFonction() {
        return $this->fonction;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setNom($nom) {
        $this->nom = strtoupper($nom);
        return $this;
    }

    public function setFonction($fonction) {
        $this->fonction = $fonction;
        return $this;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
        return $this;
    }

    public function getClub(): Club {
        return $this->club;
    }

    public function setClub(Club $club) {
        $this->club = $club;
        return $this;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }


    
}
