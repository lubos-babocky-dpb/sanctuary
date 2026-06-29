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
            attributes: ['uuid' => $request->validated('uuid')],
            values: ['user_id' => null]
        );

        if($ghost->tokens()->where(column: 'name', operator: '=', value: $this->getTokenName())->doesntExist()) {
            return $this->responseFactory->json(
                data: new HandshakeResource(
                    resource: $ghost,
                    token: $ghost->createToken(name: $this->getTokenName())->plainTextToken
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