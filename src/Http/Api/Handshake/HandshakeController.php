<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Http\Api\Handshake;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Dpb\Sanctuary\Models\Ghost;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Http\Response;

final class HandshakeController
{
    public function __construct(
        private ResponseFactory $responseFactory,
        private ConfigRepository $config
    ) {}

    public function __invoke(
        HandshakeRequest $request
    ): Response|JsonResponse {

        $ghost = Ghost::firstOrCreate(
            attributes: ['uuid' => $request->validated('uuid')]
        );

        if($ghost->tokens()->where(column: 'name', operator: '=', value: $this->getTokenName())->doesntExist()) {
            $newToken = $ghost->createToken(name: $this->getTokenName());
            return $this->responseFactory->json(
                data: new HandshakeResource(
                    resource: $ghost,
                    token: $newToken->plainTextToken,
                    expiresAt: $newToken->accessToken->expires_at
                ),
                status: 201
            );
        } else {
            return $this->responseFactory->noContent();
        }
    }

    private function getTokenName(): string {
        return $this->config->get('sanctuary.token_name', 'sanctuary-token');
    }
}