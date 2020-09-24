<?php

namespace App\Entity;

use App\Repository\AwardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Award
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="awards")
     */
    private $movie;

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

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

  

   
}
