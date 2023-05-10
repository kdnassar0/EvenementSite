<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer"   )
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_debut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_des_places;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $description ;

 
    /**
     * @ORM\Column(type="string", length=50,options={"default" : "en attente"})
     */
    private $statut;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $paiement_valid;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="evenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;



    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="evenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createur; 

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="evenements")
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="evenement")
     */
    private $commentaires;

    /**
     * @ORM\ManyToMany(targetEntity=Salle::class, mappedBy="evenements")
     */
    private $salles;

    /**
     * @ORM\Column(type="string", length=255)
     */

     /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;



    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->salles = new ArrayCollection();
    
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getNbDesPlaces(): ?int
    {
        return $this->nb_des_places;
    }

    public function setNbDesPlaces(int $nb_des_places): self
    {
        $this->nb_des_places = $nb_des_places;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description ;
    }

    public function setDescription(string $description ): self
    {
        $this->description = $description ;

        return $this;
    }

  


    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

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

    public function isPaiementValid(): ?bool
    {
        return $this->paiement_valid;
    }

    public function setPaiementValid(?bool $paiement_valid): self
    {
        $this->paiement_valid = $paiement_valid;

        return $this;
    }



    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


    public function getCreateur(): ?User
    {
        return $this->createur;
    }

    public function setCreateur(?User $createur): self
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipant(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setEvenement($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getEvenement() === $this) {
                $commentaire->setEvenement(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
 
    
    public function __toString()
    {
      return  $this->nom ." ".$this->date_fin ." ".$this->date_debut." ".$this->nb_des_places ." ".$this->prix ." ".$this->description ." ".
        $this->image ; 
    }

    /**
     * @return Collection<int, Salle>
     */
    public function getSalles(): Collection
    {
        return $this->salles;
    }

    public function addSalle(Salle $salle): self
    {
        if (!$this->salles->contains($salle)) {
            $this->salles[] = $salle;
            $salle->addEvenement($this);
        }

        return $this;
    }

    public function removeSalle(Salle $salle): self
    {
        if ($this->salles->removeElement($salle)) {
            $salle->removeEvenement($this);
        }

        return $this;
    }




}
