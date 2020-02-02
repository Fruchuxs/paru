<?php

namespace Paru\Bundles\Page\Controllers\Backend;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdatePageController {

    public function __invoke(Request $request, Response $response, array $args) {
        $response->getBody()->write('updatepage');

        return $response;
    }

}
