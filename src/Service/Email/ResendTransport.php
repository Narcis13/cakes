<?php
declare(strict_types=1);

namespace App\Service\Email;

use Cake\Core\Configure;
use Cake\Log\Log;
use Exception;
use Resend;
use Resend\Client;

/**
 * Resend Email Transport
 *
 * Implements email sending via Resend API.
 */
class ResendTransport implements EmailTransportInterface
{
    /**
     * @var \Resend\Client|null
     */
    private ?Client $client = null;

    /**
     * Initialize the Resend client
     *
     * @return \Resend\Client
     * @throws \Exception If API key is not configured
     */
    private function getClient(): Client
    {
        if ($this->client === null) {
            $apiKey = Configure::read('ApiKeys.resend');

            if (!$apiKey || $apiKey === 'your-resend-api-key-here') {
                throw new Exception('Resend API key not configured');
            }

            $this->client = Resend::client($apiKey);
        }

        return $this->client;
    }

    /**
     * @inheritDoc
     */
    public function send(
        string $to,
        string $subject,
        string $html,
        string $text,
        string $fromEmail,
        string $fromName,
    ): bool {
        try {
            $client = $this->getClient();

            $result = $client->emails->send([
                'from' => $fromName . ' <' . $fromEmail . '>',
                'to' => [$to],
                'subject' => $subject,
                'html' => $html,
                'text' => $text,
            ]);

            Log::info('Email sent via Resend to ' . $to . ', ID: ' . ($result->id ?? 'unknown'));

            return true;
        } catch (Exception $e) {
            Log::error('Resend email error: ' . $e->getMessage());
            throw $e;
        }
    }
}
