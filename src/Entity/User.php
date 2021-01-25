<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
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
     * @ORM\Column(type="string", length=180, unique=true)
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

    /**
     * @ORM\Column(type="date")
     */
    private $dateDeNaissance;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $etudeEtDiplome;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $dateDeNaissance): self
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getEtudeEtDiplome(): ?string
    {
        return $this->etudeEtDiplome;
    }

    public function setEtudeEtDiplome(?string $etudeEtDiplome): self
    {
        $this->etudeEtDiplome = $etudeEtDiplome;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
