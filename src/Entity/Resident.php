<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 01/11/2017
 * Time: 19:51
 */

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="\App\Repository\ResidentRepository")
 * @ORM\Table(name="resident")
 * @Vich\Uploadable
 */
class Resident {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
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
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $image;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the resident.
     * @Vich\UploadableField(mapping="resident_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="text", nullable=true)
     * */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * */
    private $ficheAlimentaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     * */
    private $ficheMedicale;

    /**
     * @var Transmission|ArrayCollection
     * @ORM\OneToMany(targetEntity="Transmission", mappedBy="resident")
     */
    private $transmissions;

    /**
     * @var Maisonnee
     * @ORM\ManyToOne(targetEntity="Maisonnee", inversedBy="residents")
     */
    private $maisonnee;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isActive;

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
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
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
     * @param File|null $image
     * @internal param File $imageFile
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
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
    public function getFicheAlimentaire()
    {
        return $this->ficheAlimentaire;
    }

    /**
     * @param mixed $ficheAlimentaire
     */
    public function setFicheAlimentaire($ficheAlimentaire)
    {
        $this->ficheAlimentaire = $ficheAlimentaire;
    }

    /**
     * @return mixed
     */
    public function getFicheMedicale()
    {
        return $this->ficheMedicale;
    }

    /**
     * @param mixed $ficheMedicale
     */
    public function setFicheMedicale($ficheMedicale)
    {
        $this->ficheMedicale = $ficheMedicale;
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

    /**
     * @return mixed
     */
    public function getIsActive()
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
}
