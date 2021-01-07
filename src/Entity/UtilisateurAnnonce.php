<?php

namespace App\Entity;

use App\Repository\UtilisateurAnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UtilisateurAnnonceRepository::class)
 */
class UtilisateurAnnonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="utilisateurAnnonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidat;

    /**
     * @ORM\ManyToOne(targetEntity=Annonce::class, inversedBy="utilisateurAnnonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;

    /**
     * @ORM\ManyToOne(targetEntity=StatusCandidat::class, inversedBy="utilisateurAnnonce")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statusCandidat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidat(): ?User
    {
        return $this->candidat;
    }

    public function setCandidat(?User $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function getStatusCandidat(): ?StatusCandidat
    {
        return $this->statusCandidat;
    }

    public function setStatusCandidat(?StatusCandidat $statusCandidat): self
    {
        $this->statusCandidat = $statusCandidat;

        return $this;
    }
}
