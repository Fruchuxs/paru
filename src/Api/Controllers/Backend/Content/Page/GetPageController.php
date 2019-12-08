<?php

namespace Paru\Api\Controllers\Backend\Content\Page;

use Paru\Core\Content\Page\FindPage;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\Serializer;

class GetPageController {

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var FindPage
     */
    private $findPage;

    public function __construct(FindPage $findPage, Serializer $serializer) {
        $this->findPage = $findPage;
        $this->serializer = $serializer;
    }
    
    public function __invoke(Request $request, Response $response, array $args) {
        if(!array_key_exists('name', $args)) {
            return $response->withStatus(404);
        }
        
        $result = $this->findPage->find($args['name']);
        $jsonResult = $this->serializer->serialize($result, 'json');
        $response->getBody()->write($jsonResult);
        
        return $response;
    }

}
