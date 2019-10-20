<?php

namespace Paru\Api\Controllers\Backend\Content\Page;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreatePageController {

    /**
     * @var Symfony\Component\Serializer\Serializer
     */
    private $serializer;

    /**
     * @var \Paru\Core\Content\Page\CreatePage
     */
    private $createPage;

    public function __construct(\Paru\Core\Content\Page\CreatePage $createPage, \Symfony\Component\Serializer\Serializer $serializer) {
        $this->createPage = $createPage;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request, Response $response, array $args) {
        $page = $this->serializer->deserialize(
                $request->getBody(),
                \Paru\Core\Content\Page\Page::class,
                'json');

        $this->createPage->SavePage($page);

        return $response;
    }

}
