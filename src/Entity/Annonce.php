<?php

namespace App\Entity;
use App\Form\MarqueType;
use App\Form\ModelType;
use App\Form\GarageType;
use App\Form\CarburantType;
use App\Entity\Carburant;
use App\Entity\Image;

use App\Repository\AnnonceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Metadata\ApiResource;


#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
#[Vich\Uploadable]
#[ApiResource]
class Annonce
{
    #[ORM\ManyToOne(inversedBy: 'annonce')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carburant $carburant;

    #[ORM\ManyToOne(inversedBy: 'annonce')]
    #[ORM\JoinColumn(nullable: false)]
     private ?Model $model = null;

     #[ORM\ManyToOne(inversedBy: 'annonce')]
     #[ORM\JoinColumn(nullable: false)]
    private ?Marque $marque;

    #[ORM\ManyToOne(inversedBy: 'annonce')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Garage $garage;

 //erreur pour avoir le type du carburant (entre entity et string)

    public function getcarburant(): ?Carburant
    {
        return $this->carburant; 
    }

    public function setCarburant(?Carburant $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $ref = null;

    #[ORM\Column]
    private ?int $misEnCirculation = null;

    #[ORM\Column]
    private ?int $kilometrage = null;

    #[ORM\Column]
    private ?int $prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getMisEnCirculation(): ?int
    {
        return $this->misEnCirculation;
    }

    public function setMisEnCirculation(int $misEnCirculation): static
    {
        $this->misEnCirculation = $misEnCirculation;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

   
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'annonce', cascade: ['persist', 'remove'])]
    private  $images;

    // ...

  // NOTE: This is not a mapped field of entity metadata, just a simple property.
 #[Vich\UploadableField(mapping: 'annonce_images', fileNameProperty: 'imageName')]
                                                                                                              private ?File $imageFile = null;



  #[ORM\Column(nullable: true)]
  private ?string $imageName = null;

  #[ORM\ManyToMany(targetEntity: Contact::class, mappedBy: 'Annonce')]
  private Collection $contacts;

  #[ORM\Column]
  private ?bool $CameraDeRecul = null;

  #[ORM\Column]
  private ?bool $Gps = null;

  #[ORM\Column]
  private ?bool $Bluetooth = null;

  #[ORM\Column]
  private ?bool $Climatisation = null;

  #[ORM\Column(length: 255)]
  private ?string $ControleTechnique = null;

  #[ORM\Column(length: 255)]
  private ?string $PremiereMain = null;

  #[ORM\Column(length: 255)]
  private ?string $Couleur = null;

  #[ORM\Column(length: 255)]
  private ?string $NombreDePortes = null;

  #[ORM\Column(length: 255)]
  private ?string $VolumeDeCoffre = null;

  #[ORM\Column(length: 255)]
  private ?string $Rechargeable = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $PuissanceFiscale = null;

  #[ORM\Column(length: 255)]
  private ?string $Garantie = null;
    // ...

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    
     /**
     * @param File|string|null $imageFile
     */
    public function setImageFile(File $imageFile): void
    {
        if ($imageFile instanceof File || $imageFile === null) {
        $this->imageFile = $imageFile;
        // Si un fichier est fourni, mettez à jour également la propriété imageName
        if ($imageFile instanceof File) {
            $this->setImageName($imageFile->getFilename());
        }
    } else {
        throw new \InvalidArgumentException('Invalid argument type for setImageFile.');
    }
    }


    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    // ... autres méthodes

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAnnonce($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnnonce() === $this) {
                $image->setAnnonce(null);
            }
        }

        return $this;
    }
    

    // Ajoutez cette méthode pour gérer le téléchargement du fichier
    public function uploadImage(UploadedFile $file): string
    {
        // Utilisez la classe File pour manipuler le fichier
    $fileObject = new File($file->getPathname());
    

    // Obtenez le nom du fichier
    $fileName = md5(uniqid()) . '.' . $fileObject->guessExtension();

    // Déplacez le fichier vers le répertoire souhaité
    $fileObject->move($this->getUploadDir(), $fileName);

    // Mettez à jour la propriété imageName avec le nom du fichier téléchargé
    $this->setImageName($fileName);
   

    return $fileName;
    }

    // Ajoutez cette méthode pour obtenir le répertoire de téléchargement
    private function getUploadDir(): string
    {
        // Choisissez le répertoire de destination en fonction de votre structure de projet
        return './public/media/';
    }

  /**
   * Get the value of imageName
   */ 
  public function getImageName()
  {
    return $this->imageName;
  }

  /**
   * Set the value of imageName
   *
   * @return  self
   */ 
  public function setImageName($imageName)
  {
    $this->imageName = $imageName;

    return $this;
  }

  /**
   * @return Collection<int, Contact>
   */
  public function getContacts(): Collection
  {
      return $this->contacts;
  }

  public function addContact(Contact $contact): static
  {
      if (!$this->contacts->contains($contact)) {
          $this->contacts->add($contact);
          $contact->addAnnonce($this);
      }

      return $this;
  }

  public function removeContact(Contact $contact): static
  {
      if ($this->contacts->removeElement($contact)) {
          $contact->removeAnnonce($this);
      }

      return $this;
  }

  public function isCameraDeRecul(): ?bool
  {
      return $this->CameraDeRecul;
  }

  public function setCameraDeRecul(bool $CameraDeRecul): static
  {
      $this->CameraDeRecul = $CameraDeRecul;

      return $this;
  }

  public function isGps(): ?bool
  {
      return $this->Gps;
  }

  public function setGps(bool $Gps): static
  {
      $this->Gps = $Gps;

      return $this;
  }

  public function isBluetooth(): ?bool
  {
      return $this->Bluetooth;
  }

  public function setBluetooth(bool $Bluetooth): static
  {
      $this->Bluetooth = $Bluetooth;

      return $this;
  }

  public function isClimatisation(): ?bool
  {
      return $this->Climatisation;
  }

  public function setClimatisation(bool $Climatisation): static
  {
      $this->Climatisation = $Climatisation;

      return $this;
  }

  public function getControleTechnique(): ?string
  {
      return $this->ControleTechnique;
  }

  public function setControleTechnique(string $ControleTechnique): static
  {
      $this->ControleTechnique = $ControleTechnique;

      return $this;
  }

  public function getPremiereMain(): ?string
  {
      return $this->PremiereMain;
  }

  public function setPremiereMain(string $PremiereMain): static
  {
      $this->PremiereMain = $PremiereMain;

      return $this;
  }

  public function getCouleur(): ?string
  {
      return $this->Couleur;
  }

  public function setCouleur(string $Couleur): static
  {
      $this->Couleur = $Couleur;

      return $this;
  }

  public function getNombreDePortes(): ?string
  {
      return $this->NombreDePortes;
  }

  public function setNombreDePortes(string $NombreDePortes): static
  {
      $this->NombreDePortes = $NombreDePortes;

      return $this;
  }

  public function getVolumeDeCoffre(): ?string
  {
      return $this->VolumeDeCoffre;
  }

  public function setVolumeDeCoffre(string $VolumeDeCoffre): static
  {
      $this->VolumeDeCoffre = $VolumeDeCoffre;

      return $this;
  }

  public function getRechargeable(): ?string
  {
      return $this->Rechargeable;
  }

  public function setRechargeable(string $Rechargeable): static
  {
      $this->Rechargeable = $Rechargeable;

      return $this;
  }

  public function getPuissanceFiscale(): ?string
  {
      return $this->PuissanceFiscale;
  }

  public function setPuissanceFiscale(?string $PuissanceFiscale): static
  {
      $this->PuissanceFiscale = $PuissanceFiscale;

      return $this;
  }

  public function getGarantie(): ?string
  {
      return $this->Garantie;
  }

  public function setGarantie(string $Garantie): static
  {
      $this->Garantie = $Garantie;

      return $this;
  }

 
}

