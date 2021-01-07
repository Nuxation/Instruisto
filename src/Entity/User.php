<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motDePass;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $genre;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="auteur", orphanRemoval=true)
     */
    private $annonces;

    /**
     * @ORM\OneToMany(targetEntity=UserRelation::class, mappedBy="source", orphanRemoval=true)
     */
    private $userRelations;

    /**
     * @ORM\OneToMany(targetEntity=UserRelation::class, mappedBy="destinataire", orphanRemoval=true)
     */
    private $userIsDestinataire;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="source", orphanRemoval=true)
     */
    private $messagesSource;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="destinataire", orphanRemoval=true)
     */
    private $messagesDestinataire;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="source", orphanRemoval=true)
     */
    private $commentairesSource;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="destinataire", orphanRemoval=true)
     */
    private $commentairesDestinataire;

    /**
     * @ORM\OneToMany(targetEntity=UtilisateurAnnonce::class, mappedBy="candidat", orphanRemoval=true)
     */
    private $utilisateurAnnonces;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->userRelations = new ArrayCollection();
        $this->userIsDestinataire = new ArrayCollection();
        $this->messagesSource = new ArrayCollection();
        $this->messagesDestinataire = new ArrayCollection();
        $this->commentairesSource = new ArrayCollection();
        $this->commentairesDestinataire = new ArrayCollection();
        $this->utilisateurAnnonces = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getMotDePass(): ?string
    {
        return $this->motDePass;
    }

    public function setMotDePass(string $motDePass): self
    {
        $this->motDePass = $motDePass;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setAuteur($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getAuteur() === $this) {
                $annonce->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserRelation[]
     */
    public function getUserRelations(): Collection
    {
        return $this->userRelations;
    }

    public function addUserRelation(UserRelation $userRelation): self
    {
        if (!$this->userRelations->contains($userRelation)) {
            $this->userRelations[] = $userRelation;
            $userRelation->setSource($this);
        }

        return $this;
    }

    public function removeUserRelation(UserRelation $userRelation): self
    {
        if ($this->userRelations->removeElement($userRelation)) {
            // set the owning side to null (unless already changed)
            if ($userRelation->getSource() === $this) {
                $userRelation->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserRelation[]
     */
    public function getUserIsDestinataire(): Collection
    {
        return $this->userIsDestinataire;
    }

    public function addUserIsDestinataire(UserRelation $userIsDestinataire): self
    {
        if (!$this->userIsDestinataire->contains($userIsDestinataire)) {
            $this->userIsDestinataire[] = $userIsDestinataire;
            $userIsDestinataire->setDestinataire($this);
        }

        return $this;
    }

    public function removeUserIsDestinataire(UserRelation $userIsDestinataire): self
    {
        if ($this->userIsDestinataire->removeElement($userIsDestinataire)) {
            // set the owning side to null (unless already changed)
            if ($userIsDestinataire->getDestinataire() === $this) {
                $userIsDestinataire->setDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessagesSource(): Collection
    {
        return $this->messagesSource;
    }

    public function addMessagesSource(Message $messagesSource): self
    {
        if (!$this->messagesSource->contains($messagesSource)) {
            $this->messagesSource[] = $messagesSource;
            $messagesSource->setSource($this);
        }

        return $this;
    }

    public function removeMessagesSource(Message $messagesSource): self
    {
        if ($this->messagesSource->removeElement($messagesSource)) {
            // set the owning side to null (unless already changed)
            if ($messagesSource->getSource() === $this) {
                $messagesSource->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessagesDestinataire(): Collection
    {
        return $this->messagesDestinataire;
    }

    public function addMessagesDestinataire(Message $messagesDestinataire): self
    {
        if (!$this->messagesDestinataire->contains($messagesDestinataire)) {
            $this->messagesDestinataire[] = $messagesDestinataire;
            $messagesDestinataire->setDestinataire($this);
        }

        return $this;
    }

    public function removeMessagesDestinataire(Message $messagesDestinataire): self
    {
        if ($this->messagesDestinataire->removeElement($messagesDestinataire)) {
            // set the owning side to null (unless already changed)
            if ($messagesDestinataire->getDestinataire() === $this) {
                $messagesDestinataire->setDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentairesSource(): Collection
    {
        return $this->commentairesSource;
    }

    public function addCommentairesSource(Commentaire $commentairesSource): self
    {
        if (!$this->commentairesSource->contains($commentairesSource)) {
            $this->commentairesSource[] = $commentairesSource;
            $commentairesSource->setSource($this);
        }

        return $this;
    }

    public function removeCommentairesSource(Commentaire $commentairesSource): self
    {
        if ($this->commentairesSource->removeElement($commentairesSource)) {
            // set the owning side to null (unless already changed)
            if ($commentairesSource->getSource() === $this) {
                $commentairesSource->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentairesDestinataire(): Collection
    {
        return $this->commentairesDestinataire;
    }

    public function addCommentairesDestinataire(Commentaire $commentairesDestinataire): self
    {
        if (!$this->commentairesDestinataire->contains($commentairesDestinataire)) {
            $this->commentairesDestinataire[] = $commentairesDestinataire;
            $commentairesDestinataire->setDestinataire($this);
        }

        return $this;
    }

    public function removeCommentairesDestinataire(Commentaire $commentairesDestinataire): self
    {
        if ($this->commentairesDestinataire->removeElement($commentairesDestinataire)) {
            // set the owning side to null (unless already changed)
            if ($commentairesDestinataire->getDestinataire() === $this) {
                $commentairesDestinataire->setDestinataire(null);
            }
        }

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
            $utilisateurAnnonce->setCandidat($this);
        }

        return $this;
    }

    public function removeUtilisateurAnnonce(UtilisateurAnnonce $utilisateurAnnonce): self
    {
        if ($this->utilisateurAnnonces->removeElement($utilisateurAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurAnnonce->getCandidat() === $this) {
                $utilisateurAnnonce->setCandidat(null);
            }
        }

        return $this;
    }
}
