<?php

namespace App\Entity;

use App\Repository\CarburantRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Annonce;
use Doctrine\Common\Collections\Collection;




#[ORM\Entity(repositoryClass: CarburantRepository::class)]
class Carburant
{
    #[ORM\OneToMany(targetEntity: Annonce::class, mappedBy: 'carburant')]
     private Collection $annonces;
   
     #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type) 
    {
        $this->type = $type;

        return $this;
    }

    public function __toString()
{
    return $this->type; 
}
}
