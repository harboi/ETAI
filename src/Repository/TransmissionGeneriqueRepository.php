<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 07/11/2017
 * Time: 20:55
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\TransmissionGenerique;

class TransmissionGeneriqueRepository extends EntityRepository
{
    /**
     * @param $id
     * @return TransmissionGenerique|object
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
     * @return TransmissionGenerique|array
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
     * @return TransmissionGenerique|array
     * @throws \Exception
     */
    public function getLast()
    {
        $Transmissions = $this->createQueryBuilder('t')
            ->setMaxResults('5')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()->getResult();
        if (empty(array_filter($Transmissions))) {
            throw new \Exception;
        }
        return $Transmissions;
    }

}