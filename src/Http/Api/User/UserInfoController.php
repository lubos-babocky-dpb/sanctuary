<?php
namespace Dpb\Sanctuary\Http\Api\User;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;

class UserInfoController {

    public function __construct(
        private readonly ResponseFactory $responseFactory
    ) {}

    public function __invoke(
        Request $request
    ) {
        return $this->responseFactory->json(data: new UserInfoResource(
            resource: $request->user()
        )); 
    }
}