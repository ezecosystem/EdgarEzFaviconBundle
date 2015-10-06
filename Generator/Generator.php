<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 27/09/2015
 * Time: 16:25
 */

namespace EdgarEz\FaviconBundle\Generator;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Guzzle\Http\Client;

class Generator
{
    private $configResolver;

    /**
     * Initialize main Generator object
     *
     * @param ConfigResolverInterface $configResolver config service
     */
    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * Call RealFaviconGenerator service and get response
     *
     * @param string $apiKey RealFaviconGenerator API Key
     * @param string $picturePath image base path
     * @param string $fileLocation favicons images path
     * @return QueryData response from the RealFaviconGeneration service
     */
    public function generate($apiKey, $picturePath, $fileLocation)
    {
        $parameters = array(
            'master_picture_path' => $picturePath,
            'files_location_path' => $fileLocation
        );
        $queryData = new QueryData($apiKey, $parameters);

        return $queryData;
    }
}