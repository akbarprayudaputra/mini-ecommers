<?php

namespace MiniECommers\Backend\Models;

use JsonSerializable;

class Product implements JsonSerializable
{
    private ?int $id;
    private ?string $name = null;
    private ?string $description = null;
    private ?int $category_id = null;
    private ?float $price = null;
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
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice(float $price): static
    {
        $this->price =  $price;

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

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of category_id
     */
    public function getCategory_id()
    {
        return $this->category_id;
    }

    /**
     * Set the value of category_id
     *
     * @return  self
     */
    public function setCategory_id($category_id)
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "description" => $this->getDescription(),
            "category_id" => $this->getCategory_id(),
            "price" => $this->getPrice(),
            "stockQuantity" => $this->getStockQuantity(),
        ];
    }
}
