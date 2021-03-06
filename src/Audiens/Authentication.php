<?php declare(strict_types=1);

namespace Audiens\AdForm;

use Audiens\AdForm\Exception\OauthException;
use DateTime;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;

class Authentication
{
    /** @var AccessToken */
    protected $accessToken;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /**
     * Class constructor
     *
     * @param string $username
     * @param string $password
     *
     * @throws OauthException
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->authenticate();
    }

    /**
     * Authenticate on AdForm API using the password grant
     *
     * @throws OauthException if authentication fails
     */
    public function authenticate(): void
    {
        $urlAccessToken = Client::BASE_URL.'/v1/token';

        // we are using a very simple password grant
        // AdForm doesn't even return a Refresh Token
        $provider = new GenericProvider([
            'clientId' => '',
            'clientSecret' => '',
            'redirectUri' => '',
            'urlAuthorize' => '',
            'urlAccessToken' => $urlAccessToken,
            'urlResourceOwnerDetails' => ''
        ]);

        try {
            $this->accessToken = $provider->getAccessToken('password', [
                'username' => $this->username,
                'password' => $this->password
            ]);
        } catch (IdentityProviderException $e) {
            throw OauthException::connect($e->getMessage());
        }
    }

    /**
     * Returns the Access Token, or try to re-authenticate if needed
     *
     * @return string
     *
     * @throws OauthException
     */
    public function getAccessToken(): string
    {
        // maybe the token will expire in next 10 seconds
        $expiryCutoff = new DateTime('+10 seconds');

        // if the token expires try to re-authenticate
        if (!$this->accessToken || $this->getExpires() < $expiryCutoff->getTimestamp()) {
            $this->authenticate();
        }

        return $this->accessToken->getToken();
    }

    /**
     * Returns the Expires timestamp of the Access Token
     *
     * @return int
     */
    public function getExpires(): int
    {
        return $this->accessToken->getExpires();
    }
}
