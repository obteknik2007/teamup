<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * 
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     * @var string 
     */
    private $title;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @var string 
     */
    private $content;

      /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     * @var User 
     */
    private $author;
    
    /**
     * @ORM\Column(nullable=true)
     * @Assert\Image()
     * @var string 
     */
    private $image;
    
    /**
     * @ORM\ManyToOne(targetEntity="Rencontre")
     * @ORM\JoinColumn(nullable=false)
      * @var Rencontre
     */
    private $rencontre;
    
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
   
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getAuthor(): User {
        return $this->author;
    }

    public function getImage() {
        return $this->image;
    }


    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
  
    public function setAuthor(User $author) {
        $this->author = $author;
        return $this;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }


    public function getRencontre(): ?Rencontre {
        return $this->rencontre;
    }

    public function setRencontre(Rencontre $rencontre) {
        $this->rencontre = $rencontre;
        return $this;
    }

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

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
}
