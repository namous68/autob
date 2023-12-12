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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;


#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
#[Vich\Uploadable]
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
    private $images;

    // ...

  /**
 * @var UploadedFile|null
 */
private $imageFile;

    // ...

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?UploadedFile $imageFile): void
{
    $this->imageFile = $imageFile;
}


    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    // ... autres méthodes

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnnonce($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // Si la relation est définie comme CascadeType.REMOVE, supprimez également le fichier associé ici
            // Assurez-vous d'ajouter le code nécessaire pour supprimer physiquement le fichier du répertoire
            // unlink($this->getUploadDir() . '/' . $image->getFilename());
            
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
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $path = $this->getUploadDir() . '/' . $fileName;
        // Déplacez le fichier vers le répertoire souhaité
        $file->move($this->getUploadDir(), $fileName);

        
        return $fileName;
    }

    // Ajoutez cette méthode pour obtenir le répertoire de téléchargement
    private function getUploadDir(): string
    {
        // Choisissez le répertoire de destination en fonction de votre structure de projet
        return 'images/uploads/images';
    }
}
