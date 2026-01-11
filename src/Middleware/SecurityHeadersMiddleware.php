<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Core\Configure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Security Headers Middleware
 *
 * Adds security headers to all responses to protect against common web vulnerabilities
 * including XSS, clickjacking, MIME sniffing, and more.
 */
class SecurityHeadersMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // X-Frame-Options: Prevent clickjacking attacks
        $response = $response->withHeader('X-Frame-Options', 'SAMEORIGIN');

        // X-Content-Type-Options: Prevent MIME type sniffing
        $response = $response->withHeader('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection: Enable browser XSS protection (legacy browsers)
        $response = $response->withHeader('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: Control referrer information leakage
        $response = $response->withHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content-Security-Policy: Restrict resource loading
        $csp = $this->getContentSecurityPolicy();
        $response = $response->withHeader('Content-Security-Policy', $csp);

        // Strict-Transport-Security: Enforce HTTPS (production only)
        if (!Configure::read('debug')) {
            $response = $response->withHeader(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains',
            );
        }

        return $response;
    }

    /**
     * Get Content Security Policy header value
     *
     * @return string CSP directives
     */
    protected function getContentSecurityPolicy(): string
    {
        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' " .
                'https://cdn.jsdelivr.net https://cdn.tiny.cloud https://cdnjs.cloudflare.com',
            "style-src 'self' 'unsafe-inline' " .
                'https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com',
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net data:",
            "img-src 'self' data: https:",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        return implode('; ', $directives);
    }
}
