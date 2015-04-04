<?php

namespace Ckrupa\HubicApiBundle\Api;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Buzz\Browser;
use Buzz\Message\RequestInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Ckrupa\HubicApiBundle\Exception;

class HubicApi
{
    protected $security_token_storage = null;
    protected $buzz = null;

    public function __construct(TokenStorage $security_token_storage, Browser $buzz)
    {
        $this->security_token_storage = $security_token_storage;
        $this->buzz = $buzz;
    }

    protected function getApiEndpoint()
    {
        return 'https://api.hubic.com/1.0';
    }

    protected function getAuthorizationHeaders(OAuthToken $token)
    {
        $accessToken = $token->getAccessToken();

        return array('Authorization' => ' Bearer ' . $accessToken);
    }

    public function send($path, $method = RequestInterface::METHOD_GET, array $headers = array())
    {
        if (!$this->isLoggedIn()) {
            throw new Exception\HubicApiOAuthException('unable to send api request, user is not authenticated.');
        }

        $token = $this->security_token_storage->getToken();
        $headers = array_merge($this->getAuthorizationHeaders($token), $headers);

        $url = $this->getApiEndpoint() . $path;

        /** @var \Buzz\Message\Response $response */
        $response = $this->buzz->call($url, $method, $headers);
        $json_content = $response->getContent();

        $data = json_decode($json_content, true);
        if ($response->isSuccessful()) {
            return $data;
        }

        throw new Exception\HubicApiException('invalid api response on url: ' . $url . ' error: ' . $json_content);
    }

    public function isLoggedIn()
    {
        $token = $this->security_token_storage->getToken();
        if ($token instanceof OAuthToken)
        {
            return true;
        }
        return false;
    }
}