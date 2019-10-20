<?php

namespace Paru\Api\Controllers\Backend\Content\Page;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPageController {

    public function __invoke(Request $request, Response $response, array $args) {
        $response->getBody()->write('getpage');

        return $response;
    }

}
