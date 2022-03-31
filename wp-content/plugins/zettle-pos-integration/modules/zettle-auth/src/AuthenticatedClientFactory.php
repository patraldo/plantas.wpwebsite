<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Auth;

use Http\Client\Common\Plugin\HeaderSetPlugin;
use Inpsyde\Http\HttpClientFactory;
use Inpsyde\Zettle\Auth\HTTPlug\ZettleAuthPlugin;
use Inpsyde\Zettle\Auth\Jwt\ParserInterface;
use Inpsyde\Zettle\Auth\OAuth\AuthSuccessHandler;
use Inpsyde\Zettle\Auth\OAuth\EphemeralTokenStorage;
use Inpsyde\Zettle\Auth\OAuth\Grant\GrantType;
use Inpsyde\Zettle\Auth\OAuth\Grant\JwtGrant;
use Inpsyde\Zettle\Auth\OAuth\ZettleOAuthHeader;
use Lcobucci\JWT\Parser;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class AuthenticatedClientFactory
{

    /**
     * @var HttpClientFactory
     */
    private $clientFactory;

    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var Parser
     */
    private $jwtParser;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var array
     */
    private $partnerAffiliationHeader;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @param HttpClientFactory $clientFactory
     * @param UriFactoryInterface $uriFactory
     * @param StreamFactoryInterface $streamFactory
     * @param ParserInterface $jwtParser
     * @param array $partnerAffiliationHeader
     * @param string $clientId client_id of the app, see IZET-331
     */
    public function __construct(
        HttpClientFactory $clientFactory,
        UriFactoryInterface $uriFactory,
        StreamFactoryInterface $streamFactory,
        ParserInterface $jwtParser,
        array $partnerAffiliationHeader,
        string $clientId
    ) {

        $this->clientFactory = $clientFactory;
        $this->uriFactory = $uriFactory;
        $this->jwtParser = $jwtParser;
        $this->streamFactory = $streamFactory;
        $this->partnerAffiliationHeader = $partnerAffiliationHeader;
        $this->clientId = $clientId;
    }

    /**
     * Create a client that is configured to authenticate with a given api token.
     * Useful for creating a temporary client to validate new credentials
     *
     * @param string $token
     * @param AuthSuccessHandler|null $successHandler
     *
     * @return ClientInterface
     */
    public function withApiToken(
        string $token,
        AuthSuccessHandler $successHandler = null
    ): ClientInterface {
        $authentication = new ZettleOAuthHeader(new EphemeralTokenStorage());

        $jwtGrant = new JwtGrant(
            new CredentialsContainer($this->jwtParser, ['api_key' => $token]),
            $this->jwtParser,
            $this->clientId
        );

        $plugins = [
            new ZettleAuthPlugin(
                $authentication,
                function (RequestInterface $request): bool {
                    return $this->shouldAuthenticate($request);
                },
                $this->uriFactory,
                $this->streamFactory,
                $jwtGrant,
                $jwtGrant,
                $this->ensureSuccessHandler($successHandler)
            ),
            new HeaderSetPlugin(
                $this->partnerAffiliationHeader
            ),
        ];

        return $this->clientFactory->withPlugins(...$plugins);
    }

    /**
     * @param RequestInterface $request
     *
     * @return bool
     */
    protected function shouldAuthenticate(RequestInterface $request): bool
    {
        $host = $request->getUri()->getHost();
        $path = $request->getUri()->getPath();

        if (!preg_match('/.*\.izettle\.com/', $host)) {
            return false;
        }

        if ($host === 'oauth.izettle.com' && $path !== '/users/me') {
            return false;
        }

        return true;
    }

    /**
     * @param AuthSuccessHandler|null $successHandler
     *
     * @return AuthSuccessHandler
     */
    private function ensureSuccessHandler(
        AuthSuccessHandler $successHandler = null
    ): AuthSuccessHandler {
        return $successHandler ?? new class implements AuthSuccessHandler {

            public function handle(ResponseInterface $response)
            {
                // TODO: Implement handle() method.
            }
        };
    }
}
