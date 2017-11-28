<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 07/11/2017
 * Time: 20:57
 */

namespace App\Repository;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;

class UserRepository extends EntityRepository
{
    /**
     * @param $id
     * @return User|object
     * @throws \Exception
     */
    public function findById($id)
    {
        $user = $this->find($id);
        if (null == $user) {
            throw new \Exception;
        }
        return $user;
    }

    /**
     * @return User|array
     * @throws \Exception
     */
    public function getList()
    {
        $users = $this->findAll();
        if (empty(array_filter($users))) {
            throw new \Exception;
        }
        return $users;
    }

    /**
     * @return User|array
     * @throws \Exception
     */
    public function getListActive()
    {
        $users = $this->findBy(array('isActive' => 1));
        if (empty(array_filter($users))) {
            throw new \Exception;
        }
        return $users;
    }
}