<?php

namespace App\Entity;

use App\Repository\PresentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PresentielRepository::class)
 */
class Presentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="presentiel")
     */
    private $nom;

    public function __construct()
    {
        $this->nom = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getNom(): Collection
    {
        return $this->nom;
    }

    public function addNom(Annonce $nom): self
    {
        if (!$this->nom->contains($nom)) {
            $this->nom[] = $nom;
            $nom->setPresentiel($this);
        }

        return $this;
    }

    public function removeNom(Annonce $nom): self
    {
        if ($this->nom->removeElement($nom)) {
            // set the owning side to null (unless already changed)
            if ($nom->getPresentiel() === $this) {
                $nom->setPresentiel(null);
            }
        }

        return $this;
    }
}
