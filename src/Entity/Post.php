<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
    * @ORM\ManyToMany(targetEntity="App\Entity\Movie", mappedBy="posts")
    */
    private $moviesPost;

    public function __construct()
    {
        $this->moviesPost = new ArrayCollection();
    }

    public function __toString(){

        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMoviesPost(): Collection
    {
        return $this->moviesPost;
    }

    public function addMoviesPost(Movie $moviesPost): self
    {
        if (!$this->moviesPost->contains($moviesPost)) {
            $this->moviesPost[] = $moviesPost;
            $moviesPost->addPost($this);
        }

        return $this;
    }

    public function removeMoviesPost(Movie $moviesPost): self
    {
        if ($this->moviesPost->contains($moviesPost)) {
            $this->moviesPost->removeElement($moviesPost);
            $moviesPost->removePost($this);
        }

        return $this;
    }



    
}
