<?php

namespace Paru\Bundles\Page\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListPageController {

    public function __invoke(Request $request, Response $response) {
        $response->getBody()->write('listpage');

        return $response;
    }

}
