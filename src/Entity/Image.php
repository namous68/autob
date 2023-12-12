<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Annonce;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
//#[Vich\Uploadable]
class Image
{
   

    

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $legende = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;


/**
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Veuillez télécharger une image valide (JPEG, PNG, GIF)"
     * )
     */
    private ?File $imageFile = null;



    #[ORM\ManyToOne(targetEntity: Annonce::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
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

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;

        
    }
    
    public function uploadImage(string $uploadDir)
    {
        $fileName = md5(uniqid()) . '.' . $this->imageFile->guessExtension();
        $this->imageFile->move($uploadDir, $fileName);
    }
}
