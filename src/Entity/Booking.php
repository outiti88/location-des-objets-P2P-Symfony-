<?php

namespace App\Entity;

use App\Entity\CommentClient;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date de debut doit être au bon format")
     * @Assert\GreaterThan("today", message="La date de debut doit être ulterieure à la date d'aujourd'hui")
     * @Assert\GreaterThanOrEqual(propertyPath="ad.dateDebut", message="La date de debut doit être ulterieure à la date de debut de disponibilité de l'annonce")
     * @Assert\LessThan(propertyPath="ad.dateFin", message="La date de debut doit être antérieure à la date de fin de disponibilité de l'annonce")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date de remise doit être au bon format")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date de remise doit être plus éloignée de la date de début")
     * @Assert\GreaterThan(propertyPath="ad.dateDebut", message="La date de remise doit être ulterieure à la date de debut de disponibilité de l'annonce")
     * @Assert\LessThanOrEqual(propertyPath="ad.dateFin", message="La date de remise doit être antérieure à la date de fin de disponibilité de l'annonce")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $confirm;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CommentClient", mappedBy="booking", cascade={"persist", "remove"})
     */
    private $commentClient;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vuNotifClient;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vuNotifProp;




    /**
     * Callback appelé à chaque fois qu'on crée une réservation
     *
     * @ORM\PrePersist
     * 
     * @return void
     */
    function prePersist()
    {

        if (empty($this->confirm)) {
            $this->confirm = 0;
        }

        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        if (empty($this->amount)) {
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function isBookable()
    {
        // 1) il faut savoir les dates qui sont impossible pour l'annonce
        $notAvailableDays = $this->ad->getNotAvailableDays();
        // 2) il faut comparer les dates choisies avec les dates impossible
        $bookingDays = $this->getDays();

        // Tableau des chaines de caracters de mes journées
        $days = array_map(function ($day) {
            return $day->format('Y-m-d');
        }, $bookingDays);

        // Tableau des chaines de caracters des journées non disponibles
        $notAvailable = array_map(function ($day) {
            return $day->format('Y-m-d');
        }, $notAvailableDays);

        foreach ($days as $day) {
            if (array_search($day, $notAvailable) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Permet de récuperer des journées qui correspondent à ma reservation
     *
     * @return array un tableau d'objets datetime représentant les jours de la reservation
     */
    public function getDays()
    {
        $resultat = range(
            $this->getStartDate()->getTimestamp(),
            $this->getEndDate()->getTimestamp(),
            24 * 60 * 60
        );
        $days = array_map(function ($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);

        return $days;
    }

    public function getDuration()
    {
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getConfirm(): ?int
    {
        return $this->confirm;
    }

    public function setConfirm(?int $confirm): self
    {
        $this->confirm = $confirm;

        return $this;
    }

    public function __toString()
    {
        $i = $this->id . "";
        return $i;
    }

    public function delete()
    {
        $this->setConfirm(-1);
    }

    public function getCommentClient(): ?CommentClient
    {
        return $this->commentClient;
    }

    public function setCommentClient(CommentClient $commentClient): self
    {
        $this->commentClient = $commentClient;

        // set the owning side of the relation if necessary
        if ($commentClient->getBooking() !== $this) {
            $commentClient->setBooking($this);
        }

        return $this;
    }

    public function getVuNotifClient(): ?bool
    {
        return $this->vuNotifClient;
    }

    public function setVuNotifClient(bool $vuNotifClient): self
    {
        $this->vuNotifClient = $vuNotifClient;

        return $this;
    }

    public function getVuNotifProp(): ?bool
    {
        return $this->vuNotifProp;
    }

    public function setVuNotifProp(bool $vuNotifProp): self
    {
        $this->vuNotifProp = $vuNotifProp;

        return $this;
    }
}
