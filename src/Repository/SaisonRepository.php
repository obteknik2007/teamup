<?php

namespace App\Repository;

use App\Entity\Saison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Saison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Saison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Saison[]    findAll()
 * @method Saison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaisonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Saison::class);
    }

      //Sélection des saisons du club connecté
    public function listSaisonClub($club)
    {
        return $this->createQueryBuilder('s')
            ->where('s.club = :club')->setParameter('club',$club)  
            ->getQuery()
            ->getResult()
        ;
    }  

    // retourne l'id de la dernière saison enregistrée du club connecté
    public function findIdLatestSaison($club)
    {      
            $qb = $this->createQueryBuilder('a');
 
            $qb->select('a.id')
                ->where('a.club = :club')->setParameter('club',$club)  
                ->orderBy('a.id', 'DESC')
                ->setMaxResults(1);
 
            return $qb->getQuery()
                ->getOneOrNullResult();                
            
    }
    
    // retourne le nom de la dernière saison enregistrée du club connecté
    public function findNomLatestSaison($club)
    {      
            $qb = $this->createQueryBuilder('a');
 
            $qb->select('a.nom')
                ->where('a.club = :club')->setParameter('club',$club)  
                ->orderBy('a.id', 'DESC')
                ->setMaxResults(1);
 
            return $qb->getQuery()
                ->getOneOrNullResult();                
            
    }

        /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
