<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Message = null;

    #[ORM\Column(length: 40)]
    private ?string $Name = null;

    #[ORM\ManyToMany(targetEntity: Annonce::class, inversedBy: 'contacts')]
    private Collection $Annonce;

    

    public function __construct()
    {
        $this->Annonce = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->Message;
    }

    public function setMessage(string $Message): static
    {
        $this->Message = $Message;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonce(): Collection
    {
        return $this->Annonce;
    }

    public function addAnnonce(Annonce $annonce): static
    {
        if (!$this->Annonce->contains($annonce)) {
            $this->Annonce->add($annonce);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): static
    {
        $this->Annonce->removeElement($annonce);

        return $this;
    }

    
}
