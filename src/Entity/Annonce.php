<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\AnnonceRepository;
use App\Entity\UtilisateurAnnonce;
use App\Entity\User;
use App\Entity\StatusAnnonce;
use App\Entity\Niveau;
use App\Entity\Matiere;
use App\Entity\Creneau;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Annonce
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
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $dureeEnMin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieux;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="annonce")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="Annonce")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity=StatusAnnonce::class, inversedBy="Annonce")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statusAnnonce;

    /**
     * @ORM\OneToMany(targetEntity=Creneau::class, mappedBy="annonce", orphanRemoval=true, cascade={"persist"})
     */
    private $creneaus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\OneToMany(targetEntity=UtilisateurAnnonce::class, mappedBy="annonce", orphanRemoval=true)
     */
    private $utilisateurAnnonces;

    /**
     * @ORM\ManyToOne(targetEntity=Presentiel::class, inversedBy="annonces")
     */
    private $presentiel;

    public function __construct()
    {
        $this->creneaus = new ArrayCollection();
        $this->utilisateurAnnonces = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->titre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDureeEnMin(): ?int
    {
        return $this->dureeEnMin;
    }

    public function setDureeEnMin(int $dureeEnMin): self
    {
        $this->dureeEnMin = $dureeEnMin;

        return $this;
    }

    public function getLieux(): ?string
    {
        return $this->lieux;
    }

    public function setLieux(string $lieux): self
    {
        $this->lieux = $lieux;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }


    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getStatusAnnonce(): ?StatusAnnonce
    {
        return $this->statusAnnonce;
    }

    public function setStatusAnnonce(?StatusAnnonce $statusAnnonce): self
    {
        $this->statusAnnonce = $statusAnnonce;

        return $this;
    }

    /**
     * @return Collection|Creneau[]
     */
    public function getCreneaus(): Collection
    {
        return $this->creneaus;
    }

    public function addCreneau(Creneau $creneau): self
    {
        if (!$this->creneaus->contains($creneau)) {
            $this->creneaus[] = $creneau;
            $creneau->setAnnonce($this);
        }

        return $this;
    }

    public function removeCreneau(Creneau $creneau): self
    {
        if ($this->creneaus->removeElement($creneau)) {
            // set the owning side to null (unless already changed)
            if ($creneau->getAnnonce() === $this) {
                $creneau->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection|UtilisateurAnnonce[]
     */
    public function getUtilisateurAnnonces(): Collection
    {
        return $this->utilisateurAnnonces;
    }

    public function addUtilisateurAnnonce(UtilisateurAnnonce $utilisateurAnnonce): self
    {
        if (!$this->utilisateurAnnonces->contains($utilisateurAnnonce)) {
            $this->utilisateurAnnonces[] = $utilisateurAnnonce;
            $utilisateurAnnonce->setAnnonce($this);
        }

        return $this;
    }

    public function removeUtilisateurAnnonce(UtilisateurAnnonce $utilisateurAnnonce): self
    {
        if ($this->utilisateurAnnonces->removeElement($utilisateurAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurAnnonce->getAnnonce() === $this) {
                $utilisateurAnnonce->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtPrepresist()
    {
        $this->createdAt = new \DateTime();
    }

    public function getPresentiel(): ?Presentiel
    {
        return $this->presentiel;
    }

    public function setPresentiel(?Presentiel $presentiel): self
    {
        $this->presentiel = $presentiel;

        return $this;
    }
}
