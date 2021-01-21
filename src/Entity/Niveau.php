<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
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
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="niveau")
     */
    private $Annonce;

    public function __construct()
    {
        $this->Annonce = new ArrayCollection();
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
     * @return Collection|Annonce[]
     */
    public function getAnnonce(): Collection
    {
        return $this->Annonce;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->Annonce->contains($annonce)) {
            $this->Annonce[] = $annonce;
            $annonce->setNiveau($this);
        }
        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->Annonce->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getNiveau() === $this) {
                $annonce->setNiveau(null);
            }
        }

        return $this;
    }
}
