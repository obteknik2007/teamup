<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity(fields="name", message="il existe déjà une catégorie de ce nom")
 */

class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     *@ORM\Column(length=20, unique=true)
     * @var string 
     * 
     * Validation
     * @Assert\NotBlank(message="Le nom est oblgatoire")
     * @Assert\Length(max=20,maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    private $name;

    function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Méthode magique appelée quand on essaie
     * d'accéder à l'objet comme chaine de caractère
     * par exemple avec un echo
     */
    public function __toString() 
    {
        return $this->name;
    }


}
