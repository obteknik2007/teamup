<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RencontreRepository")
 */
class Rencontre
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
     *@ORM\Column(type="date")
     * @var \Datetime
     */
    private $date;
    
    //EQUIPE DU CLUB
     /**
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumn(nullable=false)
     * @var Equipe 
     */
    private $equipe1;
     
     /**
     * @ORM\Column(type="integer",nullable=true, options={"default" : 0})) 
     * @var int
     */  
    private $equipe1Score = 0;
    
    //EQUIPE EXTERIEURE
     /**
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumn(nullable=false)
     * @var Equipe 
     */
    private $equipe2;  
    
     /**
     * @ORM\Column(type="integer",nullable=true, options={"default" : 0})) 
     * @var int
     */  
    private $equipe2Score = 0;
     /**
     * @ORM\Column(type="boolean",nullable=true)
     * * @var boolean   
     */
     private $domicile;
        
     /**
     * @ORM\Column()
     * @var string 
     */
    private $lieu;     
    
    //CLUB
     /**
     * @ORM\ManyToOne(targetEntity="Club",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @var Club 
     */
     private $club;

     //SAISON
     /**
     * @ORM\ManyToOne(targetEntity="Saison",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @var Saison 
     */
     private $saison;
 
     /**
     * @ORM\Column()
     * @var string 
     */
    private $libelle;  
     
     
    
    //pour l'enregistrement automatique de la date de création de l'enregistrement
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
    
    //SETTERS ET GETTERS
    
    public function getId() {
        return $this->id;
    }

    public function getDate(): ?\Datetime {
        return $this->date;
    }

    public function setDate(\Datetime $date) {
        $this->date = $date;
        return $this;
    }
    public function getEquipe1() {
        return $this->equipe1;
    }

    public function getEquipe2() {
        return $this->equipe2;
    }

    public function setEquipe1($equipe1) {
        $this->equipe1 = $equipe1;
        return $this;
    }

    public function setEquipe2($equipe2) {
        $this->equipe2 = $equipe2;
        return $this;
    }

    public function getLieu() {
        return $this->lieu;
    }

    public function setLieu($lieu) {
        $this->lieu = $lieu;
        return $this;
    }
    
    
    function getCreatedAt() {
        return $this->createdAt;
    }

    function getUpdatedAt() {
        return $this->updatedAt;
    }

    function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
    public function getEquipe1Score() {
        return $this->equipe1Score;
    }

    public function getEquipe2Score() {
        return $this->equipe2Score;
    }

    public function getDomicile() {
        return $this->domicile;
    }

    public function setEquipe1Score($equipe1Score) {
        $this->equipe1Score = $equipe1Score;
        return $this;
    }

    public function setEquipe2Score($equipe2Score) {
        $this->equipe2Score = $equipe2Score;
        return $this;
    }

    public function setDomicile($domicile) {
        $this->domicile = $domicile;
        return $this;
    }

        /*public function __toString() 
    {
        return $this->date;
    }*/
    
 public function getClub(): Club {
     return $this->club;
 }

 public function getSaison(): Saison {
     return $this->saison;
 }

 public function setClub(Club $club) {
     $this->club = $club;
     return $this;
 }

 public function setSaison(Saison $saison) {
     $this->saison = $saison;
     return $this;
 }

 public function getLibelle() {
     return $this->libelle;
 }

 public function setLibelle($libelle) {
     $this->libelle = $libelle;
     return $this;
 }


}
