<?php

namespace toubeelib\praticien\Repository;

use Doctrine\ORM\EntityRepository;
use toubeelib\praticien\Entity\Praticien;

class PraticienRepository extends EntityRepository
{
    /**
     * Exercice 3.2 : Liste des praticiens dont la spécialité contient un mot-clé
     * dans le libellé ou la description
     */
    public function findBySpecialiteKeyword(string $keyword): array
    {
        $dql = "SELECT p FROM toubeelib\praticien\Entity\Praticien p
                JOIN p.specialite s
                WHERE s.libelle LIKE :keyword OR s.description LIKE :keyword";

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('keyword', '%' . $keyword . '%')
            ->getResult();
    }

    /**
     * Exercice 3.3 : Liste des praticiens d'une spécialité acceptant un moyen de paiement donné
     */
    public function findBySpecialiteAndMoyenPaiement(string $specialiteLibelle, string $moyenLibelle): array
    {
        $dql = "SELECT p FROM toubeelib\praticien\Entity\Praticien p
                JOIN p.specialite s
                JOIN p.moyensPaiement m
                WHERE s.libelle = :specialite AND m.libelle = :moyen";

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('specialite', $specialiteLibelle)
            ->setParameter('moyen', $moyenLibelle)
            ->getResult();
    }
}
