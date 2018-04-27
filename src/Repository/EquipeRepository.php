<?php

namespace App\Repository;

use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Equipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipe[]    findAll()
 * @method Equipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    //Nb de joueurs par équipe du club de l'user connecté
    public function NbJoueursEquipe($club,$equipe)
    {
        return $this->createQueryBuilder('e')
            ->where('e.club = :club')->setParameter('club',$club)->setParameter('club',$club)
            ->andWhere('e.id = :id')->setParameter('id',$equipe)    
            ->getQuery()
            ->getResult()
        ;
    }
    
    //Sélection des équipes du club connecté
    public function listEquipesSaisonClub($club,$saison,bool $local = true)
    {
        return $this->createQueryBuilder('e')
            ->where('e.club = :club')->setParameter('club',$club)
            ->andWhere('e.saison = :saison')->setParameter('saison',$saison)
            ->andWhere('e.local = :local')->setParameter('local',(int)$local)    
            ->getQuery()
            ->getResult()
        ;
    }
    
    
    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('e')
            ->where('e.something = :value')->setParameter('value', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
