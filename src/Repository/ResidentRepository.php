<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 07/11/2017
 * Time: 20:54
 */

namespace App\Repository;
use Doctrine\ORM\EntityRepository;
use App\Entity\Resident;

class ResidentRepository extends EntityRepository
{
    /**
     * @param $id
     * @return Resident|object
     * @throws \Exception
     */
    public function findById($id)
    {
        $resident = $this->find($id);
        if (null == $resident) {
            throw new \Exception;
        }
        return $resident;
    }

    /**
     * @return Resident|array
     * @throws \Exception
     */
    public function getList()
    {
        $residents = $this->findAll();
        if (empty(array_filter($residents))) {
            throw new \Exception;
        }
        return $residents;
    }

    /**
     * @return Resident|array
     * @throws \Exception
     */
    public function getListActive()
    {
        $residents = $this->findBy(array('isActive' => 1));
        if (empty(array_filter($residents))) {
            throw new \Exception;
        }
        return $residents;
    }
}