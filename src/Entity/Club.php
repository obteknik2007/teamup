<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ClubRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 * @UniqueEntity(fields="nom", message="il existe déjà un club de ce nom")
 */
class Club
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
     //Date de création enregistrement
    /**
     * @ORM\Column(type="datetime",nullable=false)
     * * @var \Datetime
     */
    private $createdAt;

    //Date de modification enregistrement
    /**
     * @ORM\Column(type="datetime",nullable=true)
     * * @var \datetime
     */
    private $updatedAt;
    
    /**
     *@ORM\Column(length=200, unique=true)
     * @var string 
     * @Assert\NotBlank(message="Le nom est oblgatoire")
     * @Assert\Length(max=20,maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    private $nom;
    
     /**
     *@ORM\Column(length=4, type="string",nullable=true)
     * @var string 
     * 
     */
    private $anneeCreation;
       
    /**
     * @ORM\Column(length=15, type="string",nullable=true)
     * @var string 
     */
    private $couleurs;

    /**
     * @ORM\Column(length=6, type="string",nullable=true)
     * @var string 
     */
    private $sigle; 
    
    /**
     * @ORM\Column(nullable=true)
     * @Assert\Image()
     * @var string 
     */
    private $logo;
  
    /**
     * @ORM\Column(length=255, type="string",nullable=true)
     * @var string 
     */
    private $adresseStade;
    
    
    /**
     * @ORM\Column(length=40, type="string",nullable=true)
     * @var string 
     */
    private $emailBureau;
    
    /**
     * @ORM\Column(length=15, type="string",nullable=true)
     * @var string 
     */
    private $telephoneBureau;
    
     /**
     * @ORM\Column(unique=true)
     * @Assert\NotBlank(message="Ce champ ne doit pas etre vide")
     * @var string
     *
     */
    private $assoc;
    
    //pour l'enregistrement automatique de la date de création de l'enregistrement
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    //SETTERS ET GETTERS
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getAnneeCreation() {
        return $this->anneeCreation;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function getAdresseStade() {
        return $this->adresseStade;
    }

    public function getEmailBureau() {
        return $this->emailBureau;
    }

    public function getTelephoneBureau() {
        return $this->telephoneBureau;
    }

    public function setNom($nom) {
        $this->nom = $nom;
        return $this;
    }

    public function setAnneeCreation($anneeCreation) {
        $this->anneeCreation = $anneeCreation;
        return $this;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
        return $this;
    }

    public function setAdresseStade($adresseStade) {
        $this->adresseStade = $adresseStade;
        return $this;
    }

    public function setEmailBureau($EmailBureau) {
        $this->EmailBureau = $EmailBureau;
        return $this;
    }

    public function setTelephoneBureau($telephoneBureau) {
        $this->telephoneBureau = $telephoneBureau;
        return $this;
    }

    public function __toString() 
    {
        return $this->nom;
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

    function setUpdatedAt($updated_at) {
        $this->updatedAt = $updatedAt;
    }   

    public function getClub(): ?Club {
        return $this->club;
    }

    public function setClub(Club $club) {
        $this->club = $club;
        return $this;
    }
    function setAssoc($assoc) {
        $this->assoc = $assoc;
    }
    
    function getAssoc() {
        return $this->assoc;
    }
    public function getCouleurs() {
        return $this->couleurs;
    }

    public function setCouleurs($couleurs) {
        $this->couleurs = $couleurs;
        return $this;
    }

    public function getSigle() {
        return $this->sigle;
    }

    public function setSigle($sigle) {
        $this->sigle = $sigle;
        return $this;
    }


}
