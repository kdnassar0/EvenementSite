<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $adress;

    /**
     * @ORM\OneToMany(targetEntity=Salle::class, mappedBy="lieu")
     */
    private $salles;

    public function __construct()
    {
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }



    public function __toString()
    {
        $this->adress ." ".$this->nom ." ".$this->description ." ".$this->ville ." ".$this->date_creation ;
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
            $salle->setLieu($this);
        }

        return $this;
    }

    public function removeSalle(Salle $salle): self
    {
        if ($this->salles->removeElement($salle)) {
            // set the owning side to null (unless already changed)
            if ($salle->getLieu() === $this) {
                $salle->setLieu(null);
            }
        }

        return $this;
    }
}
