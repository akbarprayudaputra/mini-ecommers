<?php

namespace MiniECommers\Backend\Models;

class Order
{
    private ?string $orderTime = null;
    private int $customer_id;
    private ?string $status = null;

    /**
     * Get the value of orderTime
     */
    public function getOrderTime(): string|null
    {
        return $this->orderTime;
    }

    /**
     * Set the value of orderTime
     *
     * @return  self
     */
    public function setOrderTime($orderTime): static
    {
        $this->orderTime = $orderTime;

        return $this;
    }

    /**
     * Get the value of customer_id
     */
    public function getCustomer_id(): int
    {
        return $this->customer_id;
    }

    /**
     * Set the value of customer_id
     *
     * @return  self
     */
    public function setCustomer_id($customer_id): static
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string|null
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status): static
    {
        $this->status = $status;

        return $this;
    }
}
