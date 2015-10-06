<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 03/10/2015
 * Time: 15:53
 */

namespace EdgarEz\FaviconBundle\Generator;


class QueryData
{
    protected $api_key;
    protected $master_picture;
    protected $files_location;
    protected $favicon_design;
    protected $settings;

    const SCALINGALGORITHMMITCHELL        = 'Mitchell';
    const SCALINGALGORITHMNEARESTNEIGHBOR = 'NearestNeighbor';
    const SCALINGALGORITHMCUBIC           = 'Cubic';
    const SCALINGALGORITHMBILINEAR        = 'Bilinear';
    const SCALINGALGORITHMLANCZOS         = 'Lanczos';
    const SCALINGALGORITHMSPLINE          = 'Spline';

    /**
     * @param String $apiKey
     * @param array $parameters
     */
    public function __construct($apiKey, Array $parameters = array())
    {
        $this->api_key = $apiKey;
        $this->setParameters($parameters);
    }

    /**
     * @param array $parameters
     */
    public function setParameters(Array $parameters)
    {
        if (isset($parameters['master_picture_path']))
            $this->setMasterPicture($parameters['master_picture_path']);

        if(isset($parameters['files_location_path']))
            $this->setFilesLocation($parameters['files_location_path']);

        $this->setFaviconDesign();

        $compression    = (isset($parameters['settings_compression'])) ? $parameters['settings_compression'] : 3;
        $scaleAlgorithm = (isset($parameters['settings_scale_algorithm'])) ? $parameters['settings_scale_algorithm'] : self::SCALINGALGORITHMMITCHELL;
        $this->setSettings($compression, $scaleAlgorithm);
    }

    /**
     * @param String $imagePath
     */
    protected function setMasterPicture($imagePath)
    {
        if (file_exists($imagePath))
        {
            $this->master_picture = array(
                'type' => 'inline'
            );

            $image     = file_get_contents($imagePath);
            $imageData = base64_encode($image);
            $this->master_picture['content'] = $imageData;
        }
    }

    /**
     * @param String $iconsPath
     */
    protected function setFilesLocation($iconsPath)
    {
        $this->files_location = array(
            'type' => 'path',
            'path' => '/'
        );
    }

    /**
     *
     */
    protected function setFaviconDesign()
    {
        $this->favicon_design = array(
            'desktop_browser' => array(),
            'ios'             => array(),
            'windows'         => array()
            // 'firefox_app'     => array(),
            // 'android_chrome'  => array()
        );
    }

    protected function setSettings($compression = 3, $scalingAlgorithm = self::SCALINGALGORITHMMITCHELL)
    {
        $this->settings = array(
            'compression'              => $compression,
            'scaling_algorithm'        => $scalingAlgorithm,
            'error_on_image_too_small' => true
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            array(
                'favicon_generation' => array(
                    'api_key'        => $this->api_key,
                    'master_picture' => $this->master_picture,
                    'files_location' => $this->files_location,
                    'favicon_design' => $this->favicon_design,
                    'settings'       => $this->settings
                )
            )
        );
    }
}