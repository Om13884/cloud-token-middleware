<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Utils\TokenValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

final class CloudTokenMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeaderLine('Cloud-Access-Token');

        if ($token === '' || !TokenValidator::isValid($token)) {
            $response = new Response(401);
            $response->getBody()->write(json_encode(['error' => 'Invalid Cloud Access Token']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}
