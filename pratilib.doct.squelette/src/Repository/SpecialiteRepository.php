<?php

namespace toubeelib\praticien\Repository;

use Doctrine\ORM\EntityRepository;
use toubeelib\praticien\Entity\Specialite;

class SpecialiteRepository extends EntityRepository
{
    /**
     * Exercice 3.1 : Liste des spécialités contenant un mot-clé dans le libellé ou la description
     */
    public function findByKeyword(string $keyword): array
    {
        $dql = "SELECT s FROM toubeelib\praticien\Entity\Specialite s
                WHERE s.libelle LIKE :keyword OR s.description LIKE :keyword";

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('keyword', '%' . $keyword . '%')
            ->getResult();
    }
}
