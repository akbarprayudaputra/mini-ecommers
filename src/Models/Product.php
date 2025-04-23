<?php

namespace MiniECommers\Backend\Models;

class Product
{
    private ?string $name = null;
    private ?string $description = null;
    private ?int $price = null;
    private ?int $stockQuantity = null;

    /**
     * Get the value of name
     */
    public function getName(): string|null
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): string|null
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice(): int|null
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice($price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of stockQuantity
     */
    public function getStockQuantity(): int|null
    {
        return $this->stockQuantity;
    }

    /**
     * Set the value of stockQuantity
     *
     * @return  self
     */
    public function setStockQuantity($stockQuantity): static
    {
        $this->stockQuantity = $stockQuantity;

        return $this;
    }
}
