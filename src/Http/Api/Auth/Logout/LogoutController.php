<?php

declare(strict_types=1);

namespace Dpb\Sanctuary\Http\Api\Auth\Logout;

use Dpb\Sanctuary\Contracts\SanctuaryAuthenticatable;
use Dpb\Sanctuary\Exceptions\SanctuaryAuthenticatableNotSupportedException;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class LogoutController
{
    public function __construct(
        private ResponseFactory $responseFactory,
        private ConfigRepository $config,
    ) {}

    public function __invoke(
        Request $request
    ): Response {
        $ghost = Auth::guard($this->config->get('sanctuary.auth_guard', 'sanctuary_api'))
            ->user();

        ($ghost instanceof SanctuaryAuthenticatable)
            || throw new SanctuaryAuthenticatableNotSupportedException();

        $ghost->activeSession->close();
        return $this->responseFactory->noContent();
    }
}