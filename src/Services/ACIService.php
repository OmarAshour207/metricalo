<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ACIService
{
    private string $authKey = 'OGFjN2E0Yzc5Mzk0YmRjODAxOTM5NzM2ZjFhNzA2NDF8enlac1lYckc4QXk6bjYzI1NHNng='; // Hardcoded for test mode
    private string $entityId = '8ac7a4c79394bdc801939736f17e063d';     // Hardcoded test mode parameter

    public function __construct(private readonly HttpClientInterface $client)
    {}

    /**
     * Send a charge request to ACI and return a unified response.
     */
    public function charge(array $params): array
    {
        $data = [];

        $payload = [
            'entityId' => $this->entityId,
            'paymentBrand' => 'VISA',
            'paymentType' => 'DB',
            'card.holder' => 'Jane Jones',
            'amount' => $params['amount'],
            'currency' => $params['currency'],
            'card.number' => $params['card_number'],
            'card.expiryMonth' => $params['card_exp_month'],
            'card.expiryYear' => $params['card_exp_year'],
            'card.cvv' => $params['card_cvv'],
        ];

        // Send the request to ACI API endpoint (using a hypothetical URL from the docs)
        $response = $this->client->request('POST', 'https://eu-test.oppwa.com/v1/payments', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authKey,
            ],
            'body' => $payload,
            'verify_peer' => false
        ]);


        if ($response->getStatusCode() !== 200) {
            $responseContent = $response->getContent(false);
            $responseContent = json_decode($responseContent, true);
            $error = $responseContent['result']['description'];
        } else {
            $responseData = $response->toArray();
            $data['transaction_id'] = $responseData['id'];
            $data['created_at']     = $responseData['timestamp'];
            $data['amount']         = $responseData['amount'];
            $data['currency']       = $responseData['currency'];
            $data['card_bin']       = $responseData['card']['bin'];
        }

        return [
            'system' => 'aci',
            'success' => !($response->getStatusCode() !== 200),
            'error' => $error ?? null,
            'data' => $data
        ];
    }
}
