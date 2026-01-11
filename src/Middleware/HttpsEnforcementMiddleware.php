<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * HTTPS Enforcement Middleware
 *
 * Redirects HTTP requests to HTTPS in production environments.
 * Disabled when debug mode is enabled.
 */
class HttpsEnforcementMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Skip enforcement in debug mode (development)
        if (Configure::read('debug')) {
            return $handler->handle($request);
        }

        // Check if request is already HTTPS
        $uri = $request->getUri();
        if ($uri->getScheme() === 'https') {
            return $handler->handle($request);
        }

        // Check for reverse proxy headers (load balancer/CloudFlare)
        $forwardedProto = $request->getHeaderLine('X-Forwarded-Proto');
        if ($forwardedProto === 'https') {
            return $handler->handle($request);
        }

        // Redirect HTTP to HTTPS
        $httpsUri = $uri->withScheme('https')->withPort(443);
        $response = new Response();

        return $response
            ->withStatus(301)
            ->withLocation((string)$httpsUri);
    }
}
