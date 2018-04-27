<?php

namespace App\Repository;

use App\Entity\Joueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use PDO;

/**
 * @method Joueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joueur[]    findAll()
 * @method Joueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Joueur::class);
    }

    public function JoueursSaisonClub($club,$saison)
    {
        $connection=$this->getEntityManager()->getConnection();
        $statement = $connection->prepare("SELECT * FROM joueur WHERE club_id=:club_id AND saison_id=:saison_id");
        $statement->bindValue('club_id', $club,PDO::PARAM_INT);
        $statement->bindValue('saison_id', $saison,PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
       return $results;
    }
    
    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('j')
            ->where('j.something = :value')->setParameter('value', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
