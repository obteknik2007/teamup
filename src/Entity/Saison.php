<?php

namespace App\Entity;

use App\Repository\SaisonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaisonRepository")
 */
class Saison
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
     *@Assert\NotBlank(message="Ce champ ne doit pas etre vide")
     * @var \Datetime
     */
    private $dateDebut;
 
     /**
     *@ORM\Column(type="date")
     *@Assert\NotBlank(message="Ce champ ne doit pas etre vide")
     * @var \Datetime
     */
    private $datefin;   
    
     /** 
     * @var string
     * @ORM\Column(length=25)
     * @Assert\NotBlank()
     */
    private $nom;

     //pour l'enregistrement automatique de la date de création de l'enregistrement
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

     /**
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumn(nullable=false)
     * @var Club 
     */
     private $club;
     
    //GETTERS ET SETTERS
    function getId() {
        return $this->id;
    }
    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
        return $this;
    }
    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getClub(): Club {
        return $this->club;
    }

    public function setClub(Club $club) {
        $this->club = $club;
        return $this;
    }
    public function getDateDebut(): ?\Datetime {
        return $this->dateDebut;
    }

    public function getDatefin(): ?\Datetime {
        return $this->datefin;
    }

    public function setDateDebut(\Datetime $dateDebut) {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function setDatefin(\Datetime $datefin) {
        $this->datefin = $datefin;
        return $this;
    }



    public function getSaison(): ?Saison {
        return $this->saison;
    }

    public function setSaison(Saison $saison) {
        $this->saison = $saison;
        return $this;
    }
 


    
}
