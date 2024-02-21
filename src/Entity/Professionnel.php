<?php

namespace App\Entity;

use App\Repository\ProfessionnelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Annotation\IsGranted;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
/**
 * @IsGranted("ROLE_PROFESSIONAL")
 */

#[ORM\Entity(repositoryClass: ProfessionnelRepository::class)]
class Professionnel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $adressMail = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroSiret = null;

    #[ORM\Column(length: 255)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePass = null;

    #[ORM\Column]
    private ?bool $estAdmin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdressMail(): ?string
    {
        return $this->adressMail;
    }

    public function setAdressMail(string $adressMail)
    {
        $this->adressMail = $adressMail;

        return $this;
    }

    public function getNumeroTel(): ?string
    {
        return $this->numeroTel;
    }

    public function setNumeroTel(string $numeroTel)
    {
        $this->numeroTel = $numeroTel;

        return $this;
    }

    public function getNumeroSiret(): ?string
    {
        return $this->numeroSiret;
    }

    public function setNumeroSiret(?string $numeroSiret)
    {
        $this->numeroSiret = $numeroSiret;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login)
    {
        $this->login = $login;

        return $this;
    }

    public function getMotDePass(): ?string
    {
        return $this->motDePass;
    }

    public function setMotDePass(string $motDePass)
    {
        $this->motDePass = $motDePass;

        return $this;
    }

    public function isEstAdmin(): ?bool
    {
        return $this->estAdmin;
    }

    public function setEstAdmin(bool $estAdmin)
    {
        $this->estAdmin = $estAdmin;

        return $this;
    }

    public function __toString()
{
    return $this->nom;
}

}
