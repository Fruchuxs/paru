<?php

namespace Paru\Api\Controllers\Backend\Content\Page;

use Paru\Core\Content\Page\CreatePage;
use Paru\Core\Content\Page\Page;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\Serializer;

class CreatePageController {

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var CreatePage
     */
    private $createPage;

    public function __construct(CreatePage $createPage, Serializer $serializer) {
        $this->createPage = $createPage;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request, Response $response, array $args) {
        $page = $this->serializer->deserialize(
                $request->getBody(),
                Page::class,
                'json');

        $this->createPage->SavePage($page);

        return $response;
    }

}
