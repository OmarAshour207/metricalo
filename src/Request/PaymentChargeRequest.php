<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentChargeRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    #[Assert\Positive]
    private float $amount;

    #[Assert\NotBlank]
    #[Assert\Currency]
    private string $currency;

    #[Assert\NotBlank]
    #[Assert\CardScheme(schemes: ['VISA', 'MASTERCARD', 'AMEX'])]
    private string $card_number;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 2)]
    private string $card_exp_month;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    private int $card_exp_year;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 4)]
    private string $card_cvv;

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCardNumber(): string
    {
        return $this->card_number;
    }

    public function getCardExpMonth(): string
    {
        return $this->card_exp_month;
    }

    public function getCardExpYear(): int
    {
        return $this->card_exp_year;
    }

    public function getCardCvv(): string
    {
        return $this->card_cvv;
    }

    public static function fromRequest(Request $request): self
    {
        $instance = new self();

        $instance->amount = (float) $request->query->get('amount');
        $instance->currency = $request->query->get('currency');
        $instance->card_number = $request->query->get('card_number');
        $instance->card_exp_month = $request->query->get('card_exp_month');
        $instance->card_exp_year = (int) $request->query->get('card_exp_year');
        $instance->card_cvv = $request->query->get('card_cvv');

        return $instance;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'card_number' => $this->card_number,
            'card_exp_month' => $this->card_exp_month,
            'card_exp_year' => $this->card_exp_year,
            'card_cvv' => $this->card_cvv,
        ];
    }
}