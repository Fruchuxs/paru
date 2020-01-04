<?php

namespace Paru\Api\Controllers\Backend\Page;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListPageController {

    public function __invoke(Request $request, Response $response, array $args) {
        $response->getBody()->write('listpage');

        return $response;
    }

}
