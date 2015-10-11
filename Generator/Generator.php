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
     * @param string $imagePath favicons images path
     * @param array $faviconDesign list of client design
     * @param boolean $versioning define if favicon urls have suffix parameter to add GET version parameter
     * @return QueryData response from the RealFaviconGeneration service
     */
    public function generate($apiKey, $picturePath, $imagePath, $faviconDesign, $versioning)
    {
        $parameters = array(
            'master_picture_path' => $picturePath,
            'image_path'          => $imagePath,
            'favicon_design'      => $faviconDesign,
            'versioning'          => $versioning
        );
        $queryData = new QueryData($apiKey, $parameters);

        return $queryData;
    }
}