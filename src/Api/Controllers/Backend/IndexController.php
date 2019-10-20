<?php

namespace Paru\Api\Controllers\Backend;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController {

    public function __invoke(Request $request, Response $response, array $args) {
        $response->getBody()->write('backend');

        return $response;
    }

}
