<?php

namespace App\Entity;

use App\Entity\Marque;

use App\Repository\ModelRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    #[ORM\ManyToOne(inversedBy: 'models')]
    #[ORM\JoinColumn(name: 'marque_id', referencedColumnName: 'id', nullable: false)]  
      private ?Marque $marque = null;

    
    #[ORM\OneToMany(targetEntity: Model::class, mappedBy: 'marque')]
    private Collection $models;


    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }


    #[ORM\OneToMany(targetEntity: Annonce::class, mappedBy: 'model')]
    private Collection $annonces;


    /**
     * @return Collection|Model[]
     */
    public function __construct()
    {
        $this->models = new ArrayCollection();
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }
/**
     * @return Collection|Model[]
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $modelNom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModelNom(): ?string
    {
        return $this->modelNom;
    }

    public function setModelNom(string $modelNom): static
    {
        $this->modelNom = $modelNom;

        return $this;
    }

    public function __toString()
{
    return $this->modelNom; // Remplacez 'nom' par le champ que vous voulez afficher
}




    
}

