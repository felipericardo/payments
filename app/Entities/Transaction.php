<?php

namespace App\Entities;

use Carbon\Carbon;

class Transaction
{
    private ?int $id = null;
    private User $payer;
    private User $payee;
    private float $value;
    private string $status;
    private ?string $message = null;
    private ?Carbon $createdAt = null;
    private Carbon $updatedAt;

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'payer' => $this->getPayer()->toArray(),
            'payee' => $this->getPayee()->toArray(),
            'value' => $this->getValue(),
            'status' => $this->getStatus(),
            'message' => $this->getMessage(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPayer(): User
    {
        return $this->payer;
    }

    public function setPayer(User $payer): void
    {
        $this->payer = $payer;
    }

    public function getPayee(): User
    {
        return $this->payee;
    }

    public function setPayee(User $payee): void
    {
        $this->payee = $payee;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(Carbon $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
