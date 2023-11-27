<?php

namespace App\Entity;

use App\Repository\MarqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: MarqueRepository::class)]
class Marque
{
 /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="marque")
     */
    private Collection $annonces;

    /**
     * @ORM\OneToMany(targetEntity=Model::class, mappedBy="marque")
     */
    private Collection $models;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
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
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom();
    }
}
