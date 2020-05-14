<?php

namespace App\Entity;

use App\Entity\City;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
class Filter
{
    /**
     *
     */
    private $id;

    /**
     * @var City
     */
    private $city;

    /**
     *
     */
    private $startDate;

    /**
     * 
     *
     */
    private $endDate;

    /**
     * 
     */
    private $startPrice;

    /**
     * @Assert\GreaterThan(propertyPath="startPrice", message="Le Prix max doit être supérieur au prix min")
     */
    private $endPrice;

    /**
     * 
     */
    private $category;

    /**
     * 
     */
    private $subCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStartPrice(): ?string
    {
        return $this->startPrice;
    }

    public function setStartPrice(string $startPrice): self
    {
        $this->startPrice = $startPrice;

        return $this;
    }

    public function getEndPrice(): ?string
    {
        return $this->endPrice;
    }

    public function setEndPrice(string $endPrice): self
    {
        $this->endPrice = $endPrice;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSubCategory(): ?string
    {
        return $this->subCategory;
    }

    public function setSubCategory(string $subCategory): self
    {
        $this->subCategory = $subCategory;

        return $this;
    }
}
