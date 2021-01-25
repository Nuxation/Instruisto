<?php

namespace App\Entity;

use App\Repository\StatusCandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusCandidatRepository::class)
 */
class StatusCandidat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=UtilisateurAnnonce::class, mappedBy="statusCandidat")
     */
    private $utilisateurAnnonce;

    public function __construct()
    {
        $this->utilisateurAnnonce = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|UtilisateurAnnonce[]
     */
    public function getUtilisateurAnnonce(): Collection
    {
        return $this->utilisateurAnnonce;
    }

    public function addUtilisateurAnnonce(UtilisateurAnnonce $utilisateurAnnonce): self
    {
        if (!$this->utilisateurAnnonce->contains($utilisateurAnnonce)) {
            $this->utilisateurAnnonce[] = $utilisateurAnnonce;
            $utilisateurAnnonce->setStatusCandidat($this);
        }

        return $this;
    }

    public function removeUtilisateurAnnonce(UtilisateurAnnonce $utilisateurAnnonce): self
    {
        if ($this->utilisateurAnnonce->removeElement($utilisateurAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurAnnonce->getStatusCandidat() === $this) {
                $utilisateurAnnonce->setStatusCandidat(null);
            }
        }

        return $this;
    }
}
