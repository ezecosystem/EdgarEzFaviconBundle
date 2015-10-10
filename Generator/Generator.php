<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 27/09/2015
 * Time: 16:25
 */

namespace EdgarEz\FaviconBundle\Generator;

use eZ\Publish\Core\MVC\ConfigResolverInterface;

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
     * @param array $faviconDesign list of client design
     * @return QueryData response from the RealFaviconGeneration service
     */
    public function generate($apiKey, $picturePath, $fileLocation, array $faviconDesign)
    {
        $parameters = array(
            'master_picture_path' => $picturePath,
            'files_location_path' => $fileLocation,
            'favicon_design'      => $faviconDesign
        );
        $queryData = new QueryData($apiKey, $parameters);

        return $queryData;
    }
}