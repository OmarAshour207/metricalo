<?php

namespace App\Services;

class PaymentService
{
    private Shift4Service $shift4Service;
    private ACIService $aciService;

    public function __construct(Shift4Service $shift4Service, ACIService $aciService)
    {
        $this->shift4Service = $shift4Service;
        $this->aciService = $aciService;
    }

    /**
     * Charge the payment using the specified provider.
     *
     * @param string $provider "aci" or "shift4"
     * @param array $params Payment parameters.
     * @return array Unified response.
     */
    public function charge(string $provider, array $params): array
    {
        return match (strtolower($provider)) {
            'aci' => $this->aciService->charge($params),
            'shift4' => $this->shift4Service->charge($params),
            default => throw new \InvalidArgumentException("Invalid payment provider: {$provider}"),
        };
    }
}
