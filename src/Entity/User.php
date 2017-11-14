<?php
/**
 * Created by PhpStorm.
 * User: blob
 * Date: 14/10/2015
 * Time: 23:33
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="\App\Repository\UserRepository")
 * @UniqueEntity("email", message="This email is already used.")
 * @UniqueEntity("username", message="This username is already used.")
 * @Vich\Uploadable
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the user.
     *
     * @Vich\UploadableField(mapping="user_images", fileNameProperty="image")
     *
     * @var File
     */
    private $imageFile;

    /** @ORM\Column(type="boolean", nullable=false)
     */
    private $isAdmin;

    /** @ORM\Column(type="boolean", nullable=false)
     */
    private $isEducateur;

    /** @ORM\Column(type="boolean", nullable=false)
     */
    private $isSoignant;

    /** @ORM\Column(type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var Transmission|ArrayCollection
     * @ORM\OneToMany(targetEntity="Transmission", mappedBy="user")
     */
    private $transmissions;

    /**
     * @var Maisonnee
     * @ORM\ManyToOne(targetEntity="Maisonnee", inversedBy="users")
     */
    private $maisonnee;

    public function __construct()
    {
        $this->transmissions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom. " " . $this->prenom;
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

    public function getSalt()
    {
        return null;
    }

    /**
     * @return array $ROLE_USER
     */
    public function getRoles()
    {
        if ($this->isAdmin) {
            return array('ROLE_ADMIN', 'ROLE_USER');
        }

        if ($this->isEducateur && $this->isSoignant) {
            return array('ROLE_EDUCATEUR', 'ROLE_SOIGNANT', 'ROLE_USER');
        }

        if ($this->isSoignant) {
            return array('ROLE_SOIGNANT', 'ROLE_USER');
        }

        if ($this->isEducateur) {
            return array('ROLE_EDUCATEUR', 'ROLE_USER');
        }

        return array();
    }

    public function eraseCredentials()
    {
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
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * @return mixed
     */
    public function getisAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param mixed $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return mixed
     */
    public function getisEducateur()
    {
        return $this->isEducateur;
    }

    /**
     * @param mixed $isEducateur
     */
    public function setIsEducateur($isEducateur)
    {
        $this->isEducateur = $isEducateur;
    }

    /**
     * @return mixed
     */
    public function getisSoignant()
    {
        return $this->isSoignant;
    }

    /**
     * @param mixed $isSoignant
     */
    public function setIsSoignant($isSoignant)
    {
        $this->isSoignant = $isSoignant;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
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