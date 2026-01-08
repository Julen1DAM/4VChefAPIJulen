<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipes')]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $number_diner = null;

    #[ORM\ManyToOne(targetEntity: RecipeType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecipeType $recipe_type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deleted_at = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Ingredient::class, orphanRemoval: true)]
    private Collection $ingredients;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Step::class, orphanRemoval: true)]
    private Collection $steps;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Rating::class, orphanRemoval: true)]
    private Collection $ratings;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeNutrient::class, orphanRemoval: true)]
    private Collection $recipeNutrients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->recipeNutrients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getNumberDiner(): ?int
    {
        return $this->number_diner;
    }

    public function setNumberDiner(int $number_diner): static
    {
        $this->number_diner = $number_diner;
        return $this;
    }

    public function getRecipeType(): ?RecipeType
    {
        return $this->recipe_type;
    }

    public function setRecipeType(?RecipeType $recipe_type): static
    {
        $this->recipe_type = $recipe_type;
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeInterface $deleted_at): static
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }
    
    /**
     * @return Collection<int, RecipeNutrient>
     */
    public function getRecipeNutrients(): Collection
    {
        return $this->recipeNutrients;
    }
}
