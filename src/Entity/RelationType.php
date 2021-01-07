<?php

namespace App\Entity;

use App\Repository\RelationTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RelationTypeRepository::class)
 */
class RelationType
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
     * @ORM\OneToMany(targetEntity=UserRelation::class, mappedBy="relationType")
     */
    private $userRelation;

    public function __construct()
    {
        $this->userRelation = new ArrayCollection();
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
     * @return Collection|UserRelation[]
     */
    public function getUserRelation(): Collection
    {
        return $this->userRelation;
    }

    public function addUserRelation(UserRelation $userRelation): self
    {
        if (!$this->userRelation->contains($userRelation)) {
            $this->userRelation[] = $userRelation;
            $userRelation->setRelationType($this);
        }

        return $this;
    }

    public function removeUserRelation(UserRelation $userRelation): self
    {
        if ($this->userRelation->removeElement($userRelation)) {
            // set the owning side to null (unless already changed)
            if ($userRelation->getRelationType() === $this) {
                $userRelation->setRelationType(null);
            }
        }

        return $this;
    }
}
