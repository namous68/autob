<?php

namespace App\Entity;

use App\Repository\MarqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: MarqueRepository::class)]
class Marque
{
    #[ORM\OneToMany(targetEntity: Annonce::class, mappedBy: 'marque')]
    private Collection $annonces;

    #[ORM\OneToMany(targetEntity: Model::class, mappedBy: 'marque')]
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


    public function addAnnonce(Annonce $annonce): self
{
    if (!$this->annonces->contains($annonce)) {
        $this->annonces[] = $annonce;
        $annonce->setMarque($this);
    }

    return $this;
}

public function removeAnnonce(Annonce $annonce): self
{
    if ($this->annonces->removeElement($annonce)) {
        // set the owning side to null (unless already changed)
        if ($annonce->getMarque() === $this) {
            $annonce->setMarque(null);
        }
    }

    return $this;
}

public function addModel(Model $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
            $model->setMarque($this);
        }

        return $this;
    }

    public function removeModel(Model $model): self
    {
        if ($this->models->removeElement($model)) {
            // set the owning side to null (unless already changed)
            if ($model->getMarque() === $this) {
                $model->setMarque(null);
            }
        }

        return $this;
    }
}
