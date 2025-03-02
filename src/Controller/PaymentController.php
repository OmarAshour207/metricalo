<?php

namespace App\Controller;

use App\Request\PaymentChargeRequest;
use App\Services\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaymentController extends AbstractController
{
    public function __construct(private PaymentService $paymentService, private ValidatorInterface $validator)
    {
    }

    #[Route('/{provider}', name: 'provider.charge', methods: ['GET'])]
    public function charge(Request $request, string $provider): JsonResponse
    {
        $paymentRequest = PaymentChargeRequest::fromRequest($request);

        $violations = $this->validator->validate($paymentRequest);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse([
                'system' => $provider,
                'errors' => $errors
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $response = $this->paymentService->charge($provider, $paymentRequest->toArray());
            return new JsonResponse($response, $response['success'] ? JsonResponse::HTTP_OK : JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
