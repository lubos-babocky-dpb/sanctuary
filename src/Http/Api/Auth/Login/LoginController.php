<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Http\Api\Auth\Login;

use Dpb\Sanctuary\Contracts\SanctuaryAuthenticatable;
use Dpb\Sanctuary\Exceptions\SanctuaryAuthenticatableNotSupportedException;
use Dpb\Sanctuary\Repositories\AuthenticatableEntityRepository;
use Exception;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

final class LoginController
{
    public function __construct(
        private AuthenticatableEntityRepository $authenticatableEntityRepository,
        private ConfigRepository $config,
        private Hasher $hasher,
        private ResponseFactory $responseFactory
    ) {}

    public function __invoke(
        LoginRequest $request
    ): JsonResponse {
        try {
            $ghost = Auth::guard($this->config->get('sanctuary.auth_guard', 'sanctuary_api'))
                ?->user()
                ?? throw new UnauthorizedException('No ghost user found.');

            ($ghost instanceof SanctuaryAuthenticatable)
                || throw new SanctuaryAuthenticatableNotSupportedException();

            if($ghost->activeSession === null) {
                $identity = $this->authenticatableEntityRepository
                    ->findByIdentifier($request->validated('login'));

                $this->comparePasswords($request->validated('password'), $identity->password);

                $ghostSession = $ghost->createSession(identity: $identity);

                return $this->responseFactory->json(
                    data: new LoginResource($ghost),
                    status: 201
                );
            }

            $this->comparePasswords($request->validated('password'), $ghost->activeSession->authenticatable->password ?? '');

            return $this->responseFactory->json(
                data: new LoginResource(resource: $ghost),
                status: 200
            );
        } catch (UnauthorizedException $ex) {
            return $this->responseFactory->json(['message' => $ex->getMessage()], $ex->getCode());
        } catch (Exception $ex) {
            dd($ex);
            return $this->responseFactory->json(['message' => 'Internal Server Error'], 500);
        }
    }

    private function comparePasswords(
        string $rawPassword,
        string $hashedPassword
    ): void {
        if (!$this->hasher->check($rawPassword, $hashedPassword)) {
            throw new UnauthorizedException('Password is not correct', 401);
        }
    }
}