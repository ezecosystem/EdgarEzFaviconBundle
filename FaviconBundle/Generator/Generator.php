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

    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

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