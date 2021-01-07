<?php

namespace App\Entity;

use App\Repository\UserRelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRelationRepository::class)
 */
class UserRelation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userRelations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userIsDestinataire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $destinataire;

    /**
     * @ORM\ManyToOne(targetEntity=RelationType::class, inversedBy="userRelation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $relationType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?User
    {
        return $this->source;
    }

    public function setSource(?User $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDestinataire(): ?User
    {
        return $this->destinataire;
    }

    public function setDestinataire(?User $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getRelationType(): ?RelationType
    {
        return $this->relationType;
    }

    public function setRelationType(?RelationType $relationType): self
    {
        $this->relationType = $relationType;

        return $this;
    }
}
