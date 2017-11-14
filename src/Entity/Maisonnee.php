<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 07/11/2017
 * Time: 20:47
 */

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\App\Repository\MaisonneeRepository")
 * @ORM\Table(name="maisonnee")
 */
class Maisonnee
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var User|ArrayCollection
     * @ORM\OneToMany(targetEntity="User", mappedBy="maisonnee")
     */
    private $users;

    /**
     * @var Resident|ArrayCollection
     * @ORM\OneToMany(targetEntity="Resident", mappedBy="maisonnee")
     */
    private $residents;

    /**
     * @var Transmission|ArrayCollection
     * @ORM\OneToMany(targetEntity="Transmission", mappedBy="maisonnee")
     */
    private $transmissions;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->residents = new ArrayCollection();
        $this->transmissions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return Resident|ArrayCollection
     */
    public function getResidents()
    {
        return $this->residents;
    }

    /**
     * @param Resident|ArrayCollection $residents
     */
    public function setResidents($residents)
    {
        $this->residents = $residents;
    }

    /**
     * @return Transmission|ArrayCollection
     */
    public function getTransmissions()
    {
        return $this->transmissions;
    }

    /**
     * @param Transmission|ArrayCollection $transmissions
     */
    public function setTransmissions($transmissions)
    {
        $this->transmissions = $transmissions;
    }
}