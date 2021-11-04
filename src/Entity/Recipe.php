<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @UniqueEntity("name")
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @Assert\NotBlank
     */
    private $preparationTime;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @Assert\NotBlank
     */
    private $cookingTime;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $ingredientList = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $steps = [];

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="recipe")
     */
    private $comments;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $averageRate;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="recipe")
     * @Assert\Range(min = 0, max = 5)
     */
    private $rates;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favorite")
     */
    public $favorite;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPreparationTime(): ?\DateTimeInterface
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(?\DateTimeInterface $preparationTime): self
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getCookingTime(): ?\DateTimeInterface
    {
        return $this->cookingTime;
    }

    public function setCookingTime(?\DateTimeInterface $cookingTime): self
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    public function getIngredientList(): ?array
    {
        return $this->ingredientList;
    }

    public function setIngredientList(?array $ingredientList): self
    {
        $this->ingredientList = $ingredientList;

        return $this;
    }

    public function getSteps(): ?array
    {
        return $this->steps;
    }

    public function setSteps(?array $steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }

    public function getAverageRate(): ?float
    {

        $rates = $this->rates->map(fn (Rate $rate) => $rate->getScore());
        
        if (!empty($rates->toArray())) {
            return \array_sum($rates->toArray()) / $rates->count();
        } else {
            // return $this->averageRate;
            return null;
        }
    }

    /**
     * @return Collection|Rate[]
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
            $rate->setRecipe($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getRecipe() === $this) {
                $rate->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(User $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(User $favorite): self
    {
        $this->favorite->removeElement($favorite);

        return $this;
    }
    
}
