<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Produit::class);
  }

  /**
   * @throws ORMException
   * @throws OptimisticLockException
   */
  public function add(Produit $entity, bool $flush = true): void
  {
    $this->_em->persist($entity);
    if ($flush) {
      $this->_em->flush();
    }
  }

  /**
   * @throws ORMException
   * @throws OptimisticLockException
   */
  public function remove(Produit $entity, bool $flush = true): void
  {
    $this->_em->remove($entity);
    if ($flush) {
      $this->_em->flush();
    }
  }


  public function getProduitByName($saisie)
  {
    return $this->createQueryBuilder('a')
      ->andWhere('a.nom LIKE :val')
      ->setParameter('val', "%$saisie%")
      ->orderBy('a.nom', 'ASC')
      ->getQuery()
      ->getResult();
  }


  public function getProduitParOrdreAlpha()
  {

    return $this->createQueryBuilder('b')
      ->orderBy('b.nom', 'ASC')
      ->getQuery()
      ->getResult();
  }
}
