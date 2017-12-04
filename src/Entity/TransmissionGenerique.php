<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 04/12/2017
 * Time: 19:51
 *
 * Créer un nouveau type de Transmission de type générique, uniquement disponible pour l'admin avec
 * - Date Heure
 * - Maisonnée(s) concernée(s) : Toutes | Maisonnée 1 | Maisonnée 2....
 * - Message
 * - Statut : Infos (par défaut) | Important | Urgent
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\App\Repository\TransmissionGeneriqueRepository")
 * @ORM\Table(name="transmissionGenerique")
 */
class TransmissionGenerique
{
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
     * Info (par défaut) | Important | Urgent
     * @ORM\Column(type="string", nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @var Maisonnee
     * @ORM\ManyToOne(targetEntity="Maisonnee")
     */
    private $maisonnee;

    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW');
        $this->statut = 'Informatif';
    }

    public function __toString()
    {
        return $this->statut;
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
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
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
