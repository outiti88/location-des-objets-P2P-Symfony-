<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentClientRepository")
 */
class CommentClient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Booking", inversedBy="commentClient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="commentClient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="commentClient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $positiveComment;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $negativeComment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(Booking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getPositiveComment(): ?string
    {
        return $this->positiveComment;
    }

    public function setPositiveComment(string $positiveComment): self
    {
        $this->positiveComment = $positiveComment;

        return $this;
    }

    public function getNegativeComment(): ?string
    {
        return $this->negativeComment;
    }

    public function setNegativeComment(string $negativeComment): self
    {
        $this->negativeComment = $negativeComment;

        return $this;
    }

   

      /**
     * @Assert\Callback
     */
    public function validateFields(ExecutionContextInterface $context)
    {
        if ('' === $this->positiveComment && '' === $this->negativeComment) {
            $context->addViolation('Un commentaire positif ou bien négatif doit être ajouté');
        }
    }
    private $content;
    public function getContent(): ?string
    {   
        if(!$this->positiveComment && !$this->negativeComment)
        return "<strong>vous avez rien écrit</strong>";
        if($this->positiveComment && $this->negativeComment)
        return "<strong>Positive:</strong> ".$this->positiveComment."
        </br>
        <strong>negative:</strong> ".$this->negativeComment ;
        else 
        return $this->negativeComment?"<p> negative: ".$this->negativeComment."</p>":"<p> Positive: ".$this->positiveComment."</p>";
    }

    public function __toString(){
        return $this->getContent();
    }
}
