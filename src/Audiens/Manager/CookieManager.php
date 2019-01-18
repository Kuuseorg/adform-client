<?php declare(strict_types=1);

namespace Audiens\AdForm\Manager;

use Audiens\AdForm\Cache\CacheInterface;
use Audiens\AdForm\HttpClient;

class CookieManager
{
    /** @var HttpClient */
    protected $httpClient;

    /** @var CacheInterface */
    protected $cache;

    /** @var string */
    protected $cachePrefix = 'cookie';

    public function __construct(HttpClient $httpClient, ?CacheInterface $cache = null)
    {
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    /**
     * Upload cookies to AdForm
     */
    public function upload(int $providerId, string $data)
    {
        $uri = sprintf('v1/dataproviders/%d/cookies', $providerId);

        $options = [
            'body' => $data,
        ];

        return $this->httpClient->post($uri, $options)->getBody()->getContents();
    }
}
