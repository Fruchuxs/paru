<?php

namespace Paru\Api\Controllers\Backend\Content\Page;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeletePageController {

    public function __invoke(Request $request, Response $response, array $args) {
        return $response->write($request->getUri());
    }

}
