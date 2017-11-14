<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 07/11/2017
 * Time: 20:52
 */

namespace App\Repository;
use Doctrine\ORM\EntityRepository;
use App\Entity\Maisonnee;


class MaisonneeRepository extends EntityRepository
{
    /**
     * @param $id
     * @return Maisonnee|object
     * @throws \Exception
     */
    public function findById($id)
    {
        $maisonnee = $this->find($id);
        if (null == $maisonnee) {
            throw new \Exception;
        }
        return $maisonnee;
    }

    /**
     * @return Maisonnee|array
     * @throws \Exception
     */
    public function getList()
    {
        $maisonnees = $this->findAll();
        if (empty(array_filter($maisonnees))) {
            throw new \Exception;
        }
        return $maisonnees;
    }
}