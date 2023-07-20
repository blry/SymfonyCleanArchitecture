<?php

declare(strict_types=1);

namespace App\Infrastructure\OAuth2\Client\Telegram;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\ResponseInterface;
use App\Domain\Common\DomainException;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class TelegramProvider extends AbstractProvider
{
    public function getAccessToken($grant, array $options = []): AccessTokenInterface
    {
        if (!is_string($options['code'])) {
            throw new DomainException('telegram-code-must-be-string', 'Code must be string!');
        }

        return new AccessToken(['access_token' => $options['code']]);
    }

    protected function fetchResourceOwnerDetails(AccessToken $token): array
    {
        $code = $token->getToken();

        $details = [];
        parse_str(urldecode($code), $details);

        $this->verifyDetails($details);

        return $details;
    }

    private function verifyDetails(array $details): void
    {
        if (!isset($details['id']) || !isset($details['first_name']) || !isset($details['auth_date']) || !isset($details['hash'])) {
            throw new DomainException('telegram-details-error', 'Code must have id, name, auth_date, hash!');
        }

        if (count($details) !== count(array_filter($details, 'is_string'))) {
            throw new DomainException('telegram-details-format', 'Details must be string');
        }

        if ((time() - $details['auth_date']) > 86400) {
            throw new DomainException('telegram-outdated-data', 'Data is outdated');
        }

        $this->verifyDetailsHash($details);
    }

    private function verifyDetailsHash(array $details): void
    {
        $secretKey = hash('sha256', $this->clientSecret, true);
        $checkHash = $details['hash'];

        $checkDetailsArray = [];
        unset($details['hash']);
        foreach ($details as $key => $val) {
            $checkDetailsArray[] = $key . '=' . $val;
        }
        sort($checkDetailsArray);

        $payload = implode("\n", $checkDetailsArray);

        $hash = hash_hmac('sha256', $payload, $secretKey);

        if (strcmp($hash, $checkHash) !== 0) {
            throw new DomainException('telegram-invalid-hash', 'Invalid telegram hash');
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        $resourceOwner = new TelegramResourceOwner($response['id'], $response['username'], $response['first_name']);
        if (isset($response['last_name'])) {
            $resourceOwner->setLastName($response['last_name']);
        }
        if (isset($response['email'])) {
            $resourceOwner->setEmail($response['email']);
        }

        return $resourceOwner;
    }

    protected function getDefaultScopes(): array
    {
        return [];
    }

    public function getBaseAuthorizationUrl(): string
    {
        throw new \Exception('Must not be called');
    }

    /**
     * @inheritDoc
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        throw new \Exception('Must not be called');
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        throw new \Exception('Must not be called');
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        throw new \Exception('Must not be called');
    }
}
