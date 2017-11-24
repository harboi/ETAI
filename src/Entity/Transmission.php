<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 01/11/2017
 * Time: 19:51
 */

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\App\Repository\TransmissionRepository")
 * @ORM\Table(name="transmission")
 */
class Transmission {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * 0 = edicative | 1 = soin
     * @ORM\Column(type="boolean")
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $alerteSoin;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="transmissions")
     */
    private $user;

    /**
     * @var integer
     */
    private $owner;

    /**
     * @var Resident
     * @ORM\ManyToOne(targetEntity="Resident", inversedBy="transmissions")
     */
    private $resident;

    /**
     * @var Maisonnee
     * @ORM\ManyToOne(targetEntity="Maisonnee", inversedBy="transmissions")
     */
    private $maisonnee;

    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW');
        $this->owner = $this->user;
    }

    public function __toString()
    {
        return substr($this->description, 0, 20)."...";
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
    public function getAlerteSoin()
    {
        return $this->alerteSoin;
    }

    /**
     * @param mixed $alerteSoin
     */
    public function setAlerteSoin($alerteSoin)
    {
        $this->alerteSoin = $alerteSoin;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getOwner()
    {
        return $this->getUser()->getId();
    }

    /**
     * @param int $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return Resident
     */
    public function getResident()
    {
        return $this->resident;
    }

    /**
     * @param Resident $resident
     */
    public function setResident($resident)
    {
        $this->resident = $resident;
    }

    /**
     * @return Maisonnee
     */
    public function getMaisonnee()
    {
        return $this->maisonnee;
    }

    /**
     * @param Maisonnee $maisonnee
     */
    public function setMaisonnee($maisonnee)
    {
        $this->maisonnee = $maisonnee;
    }

}
