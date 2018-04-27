<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(length=20)
     * @var string 
     * 
     * Validation
     * @Assert\NotBlank(message="Le nom est oblgatoire")
     * @Assert\Length(max=20,maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    private $nom;
    
     /** 
     * @var string
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     */
    private $prenom;
    
     /**
     * @ORM\Column(unique=true)
     * @Assert\NotBlank(message="Ce champ ne doit pas etre vide")
     * @Assert\Email(message="Cet email n'est pas valide")
     * @var string
     */
    private $mel;
    
    /**
     * @var string
     * @ORM\Column(length=20)
     * @Assert\NotBlank(message="Ce champ ne doit pas etre vide")
     */
    private $telephone;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @var string 
     */
    private $message;
    
    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function getMel() {
        return $this->mel;
    }

    function getMessage() {
        return $this->message;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    function setMel($mel) {
        $this->mel = $mel;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function getTelephone() {
        return $this->telephone;
    }

    function setTelephone($telephone) {
        $this->telephone = $telephone;
    }


}
