<?php

namespace App\Entity;

use App\Repository\MovieActorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=MovieActorRepository::class)
 */
class MovieActor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $character_name;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="movieActors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Movie;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="movieActors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    public function __toString(){

        return $this->character_name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacterName(): ?string
    {
        return $this->character_name;
    }

    public function setCharacterName(string $character_name): self
    {
        $this->character_name = $character_name;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->Movie;
    }

    public function setMovie(?Movie $Movie): self
    {
        $this->Movie = $Movie;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }
}
