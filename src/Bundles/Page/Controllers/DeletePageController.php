<?php

namespace Paru\Bundles\Page\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeletePageController {

    public function __invoke(Request $request, Response $response, string $name) {
        return $response->write($request->getUri());
    }

}
