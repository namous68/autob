<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Annonce;
use APP\Entity\ImageHandler;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $legende = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    // Ajoutez cette propriété virtuelle
    private $imageFile;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    private ?UploadedFile $uploadedFile = null;

    #[ORM\ManyToOne(targetEntity: Annonce::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false, name: 'annonce_id')]
    private $annonce;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLegende(): ?string
    {
        return $this->legende;
    }

    public function setLegende(string $legende): static
    {
        $this->legende = $legende;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setImagePath(?string $imagePath): void
    {
        $this->path = $imagePath;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    public function setUploadedFile(?UploadedFile $uploadedFile): void
    {
        $this->uploadedFile = $uploadedFile;
    }

    public function uploadImage($file)
    {
        // Générer un nom de fichier unique pour l'image
        $filename = md5(uniqid()).'.'.$file->guessExtension();

        // Déplacer le fichier dans le dossier d'upload
        $file->move(
            $this->getUploadRootDir(),
            $filename
        );

        // Mettre à jour l'attribut "path" avec le nom de fichier unique
        $this->path = $filename;
    }

    public function getUploadRootDir()
    {
        // Renvoyer le chemin absolu du dossier d'upload
        return __DIR__.'./assets/js/images/uploads/images';
    }

    private function generateUniqueFileName(UploadedFile $file): string
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $uniqueName = md5(uniqid());

        return $uniqueName . '.' . $extension;
    }

    // Obtenez le répertoire de téléchargement
    private function getUploadDir(): string
    {
        return './assets/js/images/uploads/images';
    }

    public function createImage($imageFile)
    {
        $image = new Image($imageFile);
        $image->setImageFile($imageFile);
        $image->uploadImage($imageFile);

        // ...
    }

    public function getImageUrl()
{
    // Récupérer le chemin du fichier d'image
    $path = $this->getPath();

    // Construire l'URL de l'image
    $baseUrl = './assets/js/images/uploads/images';
    $imageUrl = $baseUrl . $path;

    return $imageUrl;
}
}
