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
}