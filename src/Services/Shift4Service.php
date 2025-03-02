<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Shift4Service
{
    private string $authKey = 'pr_test_tXHm9qV9qV9bjIRHcQr9PLPa:';

    public function __construct(private readonly HttpClientInterface $client)
    {}

    /**
     * Sending a request charge to Shift4 and return a unified response.
     */
    public function charge(array $params): array
    {
        $data = [];
        $payload = [
            'amount' => $params['amount'] * 100,
            'currency' => $params['currency'],
            'card' => [
                'number' => $params['card_number'],
                'expMonth' => $params['card_exp_month'],
                'expYear' => $params['card_exp_year'],
                'cvc' => $params['card_cvv']
            ]
        ];

        $response = $this->client->request('POST', 'https://api.shift4.com/charges', [
            'auth_basic' => [ $this->authKey ],
            'body' => $payload,
            'verify_peer' => false
        ]);

        if ($response->getStatusCode() !== 200) {
            $responseContent = $response->getContent(false);
            $responseContent = json_decode($responseContent, true);
            $error = $responseContent['error']['message'];
        } else {
            $responseData = $response->toArray();

            $dateTime = new \DateTime();
            $dateTime->setTimestamp($responseData['created']);
            $readableDate = $dateTime->format('Y-m-d H:i:s');

            $data['transaction_id'] = $responseData['id'];
            $data['created_at']     = $readableDate;
            $data['amount']         = $responseData['amount'] / 100;
            $data['currency']       = $responseData['currency'];
            $data['card_bin']       = $responseData['card']['first6'];
        }

        return [
            'system' => 'shift4',
            'success' => !($response->getStatusCode() !== 200),
            'error' => $error ?? null,
            'data' => $data
        ];

    }
}
