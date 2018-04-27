<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JoueurRepository")
 */
class Joueur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
     /**
     * @var string
     * @ORM\Column(length=255)
     */
    private $nom;
    
   /**
     * @var string
     * @ORM\Column(length=255)
     */
    private $prenom;


    /**
     * @var string
     * @ORM\Column(length=255)
     */
    private $rue;
    
    /**
     * @ORM\Column(length=5,type="string")
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=5,maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    private $cp;
    
     /**
     * @var string
     * @ORM\Column(length=30)
     * Assert\NotBlank()
     * @Assert\Length(max=30,maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    private $ville;
    
   
    /**
     * @ORM\Column(length=14,type="string",nullable=true)
     * @var string
     * @Assert\Length(max=14,maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    private $tel1;
    
    /**
    * @ORM\Column(length=14,type="string",nullable=true)
    * @var string
    */
    private $tel2;
    
    
    /**
     * @var string
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $dateNaissance;
    
     /**
     * @var string
     * @ORM\Column(type="array")
     * @Assert\NotBlank()
     */
    private $genre;
    
     /**
     * @ORM\Column(nullable=true)
     * @Assert\Image()
     * @var string
     */
    private $image;
        
     /**
     * @ORM\Column(nullable=true)
     * @Assert\File(mimeTypes={ "application/pdf" })
     * @var string
     */
    private $certificat;
    
    //CHOIX EQUIPE
     /**
     * @ORM\ManyToOne(targetEntity="Equipe", inversedBy="joueurs",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @var Equipe 
      * @Assert\NotBlank()
     */
     private $equipe;   
     
     
   
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
   
     
     
     //USER
     /**
     * @ORM\OneToOne(targetEntity="User",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @var User 
     */
     private $user;
     
     public function __construct() {
         $this->user = new User();
     }

     
    //GETTERS ET SETTERS
    public function getId() {
        return $this->id;
    }


    public function getRue() {
        return $this->rue;
    }

    public function getCp() {
        return $this->cp;
    }

    public function getVille() {
        return $this->ville;
    }

    public function getTel1() {
        return $this->tel1;
    }

    public function getTel2() {
        return $this->tel2;
    }

    public function getDateNaissance() {
        return $this->dateNaissance;
    }

    public function setRue($rue) {
        $this->rue = $rue;
        return $this;
    }

    public function setCp($cp) {
        $this->cp = $cp;
        return $this;
    }

    public function setVille($ville) {
        $this->ville = $ville;
        return $this;
    }

    public function setTel1($tel1) {
        $this->tel1 = $tel1;
        return $this;
    }

    public function setTel2($tel2) {
        $this->tel2 = $tel2;
        return $this;
    }

    public function setDateNaissance($dateNaissance) {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    public function getCertificat() {
        return $this->certificat;
    }

    public function setCertificat($certificat) {
        $this->certificat = $certificat;
        return $this;
    }

    public function getGenre() {
        return $this->genre;
    }

    public function setGenre($genre) {
        $this->genre = $genre;
        return $this;
    }

    public function getClub(): Club {
        return $this->club;
    }

    public function setClub(Club $club) {
        $this->club = $club;
        return $this;
    }
    public function getEquipe(){
        return $this->equipe;
    }

    public function setEquipe($equipe) {
        $this->equipe = $equipe;
        return $this;
    }
 
 
    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }

    //affiche le nom et prenom de l utilisateur si il est connecté
    /*public function getFullname()
    {
        return $this->prenom . ' ' . $this->nom;
    }*/
    public function getSaison() {
        return $this->saison;
    }

    public function setSaison($saison) {
        $this->saison = $saison;
        return $this;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function setNom($nom) {
        $this->nom = strtoupper($nom);
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    
    public function getFullName()
    {
        return trim($this->getNom() . ' ' . $this->getprenom());
    }


}
