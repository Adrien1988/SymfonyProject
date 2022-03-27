<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
/**
 * @Vich\Uploadable
 */
class Produit
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  private $nom;

  #[ORM\Column(type: 'text')]
  private $description;

  #[ORM\Column(type: 'float')]
  private $prix;

  #[ORM\Column(type: 'string', length: 255)]
  private $image;

  #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'produit')]
  #[ORM\JoinColumn(nullable: false)]
  private $categorie;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produit')]
  #[ORM\JoinColumn(nullable: false)]
  private $auteur;

  #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Commentaire::class, orphanRemoval: true)]
  private $commentaire;

  /**
   * @Vich\UploadableField(mapping= "produit_images", fileNameProperty= "image")
   */
  private $imageFile;

  #[ORM\Column(type: 'datetime')]
  private $updated_at; // cette propriété va réceptionner


  public function __construct()
  {
    $this->comments = new ArrayCollection();
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

  public function getPrix(): ?float
  {
    return $this->prix;
  }

  public function setPrix(float $prix): self
  {
    $this->prix = $prix;

    return $this;
  }

  public function getImage(): ?string
  {
    return $this->image;
  }

  public function setImage(?string $image): self
  {
    $this->image = $image;

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
   * @return Collection<int, Commentaire>
   */
  public function getCommentaire(): Collection
  {
    return $this->commentaire;
  }

  public function addCommentaire(Commentaire $commentaire): self
  {
    if (!$this->commentaire->contains($commentaire)) {
      $this->commentaire[] = $commentaire;
      $commentaire->setProduit($this);
    }

    return $this;
  }

  public function removeCommentaire(Commentaire $commentaire): self
  {
    if ($this->commentaire->removeElement($commentaire)) {
      // set the owning side to null (unless already changed)
      if ($commentaire->getProduit() === $this) {
        $commentaire->setProduit(null);
      }
    }

    return $this;
  }

  public function getImageFile(): ?File
  {
    return $this->imageFile;
  }

  public function setImageFile(?File $imageFile = null): self
  // le ? indique que le parametre devant lequel il se trouve peut être null
  {
    $this->imageFile = $imageFile;

    if ($this->imageFile instanceof UploadedFile) {
      $this->updated_at = new \DateTime('now');
      // dans le cas où on upload une image, on modifie la date de mise à jour
    }
    return $this;
  }

  public function getUpdatedAt(): ?\DateTimeInterface
  {
    return $this->updated_at;
  }

  public function setUpdatedAt(\DateTimeInterface $updated_at): self
  {
    $this->updated_at = $updated_at;

    return $this;
  }
}
