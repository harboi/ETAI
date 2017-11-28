<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 07/11/2017
 * Time: 20:55
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Transmission;

class TransmissionRepository extends EntityRepository
{
    /**
     * @param $id
     * @return Transmission|object
     * @throws \Exception
     */
    public function findById($id)
    {
        $transmission = $this->find($id);
        if (null == $transmission) {
            throw new \Exception;
        }
        return $transmission;
    }

    /**
     * @return Transmission|array
     * @throws \Exception
     */
    public function getList()
    {
        $transmissions = $this->findAll();
        if (empty(array_filter($transmissions))) {
            throw new \Exception;
        }
        return $transmissions;
    }

    /**
     * @return Transmission|array
     * @throws \Exception
     */
    public function getListWithAlerteSoin()
    {
        $Transmissions = $this->createQueryBuilder('t')
            ->setMaxResults('10')
            ->addSelect('t')
            ->where('t.type = 0')
            ->andWhere('t.alerteSoin IS NOT NULL')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()->getResult();
        if (empty(array_filter($Transmissions))) {
            throw new \Exception;
        }
        return $Transmissions;
    }

    /**
     * @return Transmission|array
     * @throws \Exception
     */
    public function getListFromParameters($start, $end, $resident = null, $personnel = null, $maisonnee = null)
    {
        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $Transmissions = $this->createQueryBuilder('t')
            ->addSelect('t')
            ->where('t.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        if (!empty($resident)) {
            $Transmissions = $Transmissions->andWhere("t.resident = :resident")
                ->setParameter('resident', $resident);
        }

        if (!empty($personnel)) {
            $Transmissions = $Transmissions->andWhere("t.user = :personnel")
                ->setParameter('personnel', $personnel);
        }

        if (!empty($maisonnee)) {
            $Transmissions = $Transmissions->andWhere("t.maisonnee = :maisonnee")
                ->setParameter('maisonnee', $maisonnee);
        }

        $Transmissions = $Transmissions->getQuery()->getResult();
        /**
         * $Commerces = $this->createQueryBuilder('c')
         * ->addSelect('t')
         * ->join('c.tags', 't')
         * ->where('c.departement = :departement')
         * ->andWhere("t.id IN(:tag)")
         * ->setParameter('departement', $departement)
         * ->setParameter('tag', $tag)
         * ->orderBy('c.id', 'DESC')
         * ->setMaxResults('100')
         * ->getQuery()
         * ->getResult();
         */
        if (empty(array_filter($Transmissions))) {
            throw new \Exception;
        }
        return $Transmissions;
    }
}