<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Http\Api\Auth;

use Dpb\Sanctuary\Models\Ghost;
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
    /**
     * Summary of __construct
     * @param AuthenticatableEntityRepository $authenticatableEntityRepository
     * @param ConfigRepository $config
     * @param Hasher $hasher
     * @param ResponseFactory $responseFactory
     */
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
            /** @var Ghost */
            $ghost = Auth::guard($this->config->get('sanctuary.auth_guard', 'sanctuary_api'))
                ?->user() ?? throw new UnauthorizedException('No ghost user found.');

            if(empty($ghost->user)) {
                //[LB:] This ghost does not have a user yet, lets find user with credentials
                $identity = $this->authenticatableEntityRepository
                    ->findByIdentifier($request->validated('login'));
                $this->comparePasswords($request->validated('password'), $identity->password);
                //[LB:] Passwords are ok, attach user to ghost
                $ghost->attachIdentity($identity)->save();
                return $this->responseFactory->json(
                    data: new LoginResource($ghost),
                    status: 201
                );
            }
            $this->comparePasswords($request->validated('password'), $ghost->user?->password ?? '');

            return $this->responseFactory->json(
                data: new LoginResource(resource: $ghost),
                status: 200
            );
        } catch (UnauthorizedException $ex) {
            return $this->responseFactory->json(['message' => $ex->getMessage()], $ex->getCode());
        } catch (Exception $ex) {
            return $this->responseFactory->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Summary of comparePasswords
     * @param string $rawPassword
     * @param string $hashedPassword
     * @throws UnauthorizedException
     * @return void
     */
    private function comparePasswords(
        string $rawPassword,
        string $hashedPassword
    ): void {
        if (!$this->hasher->check($rawPassword, $hashedPassword)) {
            throw new UnauthorizedException('Password is not correct', 401);
        }
    }
}