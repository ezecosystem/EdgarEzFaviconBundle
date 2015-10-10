<?php

namespace EdgarEz\FaviconBundle\Generator;

class QueryData
{
    protected $api_key;
    protected $master_picture;
    protected $files_location;
    protected $favicon_design;
    protected $settings;
    protected $versioning;

    private $faviconDesign = array(
        'desktop_browser', 'ios', 'windows', 'safari_pinned_tab', 'coast', 'open_graph', 'yandex_browser', 'firefox_app', 'android_chrome'
    );

    const SCALINGALGORITHMMITCHELL        = 'Mitchell';
    const SCALINGALGORITHMNEARESTNEIGHBOR = 'NearestNeighbor';
    const SCALINGALGORITHMCUBIC           = 'Cubic';
    const SCALINGALGORITHMBILINEAR        = 'Bilinear';
    const SCALINGALGORITHMLANCZOS         = 'Lanczos';
    const SCALINGALGORITHMSPLINE          = 'Spline';

    /**
     * Initialize Query object used to call realfavicongenerator api
     *
     * @param string $apiKey RealFaviconGenerator API key
     * @param array $parameters additional parameters
     */
    public function __construct($apiKey, Array $parameters = array())
    {
        $this->api_key = $apiKey;
        $this->setParameters($parameters);
    }

    /**
     * Initialize Query parameters
     *
     * @param array $parameters additional parameters
     */
    public function setParameters(Array $parameters)
    {
        if (isset($parameters['master_picture_path']))
            $this->setMasterPicture($parameters['master_picture_path']);

        if (isset($parameters['image_path']))
            $this->setFilesLocation($parameters['image_path']);

        if (isset($parameters['favicon_design']) && count($parameters['favicon_design']) > 0)
            $this->setFaviconDesign($parameters['favicon_design']);

        if (isset($parameters['versioning']))
            $this->setVersioning($parameters['versioning']);

        $compression    = (isset($parameters['settings_compression'])) ? $parameters['settings_compression'] : 3;
        $scaleAlgorithm = (isset($parameters['settings_scale_algorithm'])) ? $parameters['settings_scale_algorithm'] : self::SCALINGALGORITHMMITCHELL;
        $this->setSettings($compression, $scaleAlgorithm);
    }

    /**
     * Define where to find master picture used to generate favicons
     *
     * @param string $imagePath image path
     */
    protected function setMasterPicture($imagePath)
    {
        if (file_exists($imagePath)) {
            $this->master_picture = array(
                'type' => 'inline'
            );

            $image     = file_get_contents($imagePath);
            $imageData = base64_encode($image);
            $this->master_picture['content'] = $imageData;
        }
    }

    /**
     * Define where favicons images would be created
     *
     * @param string $imagePath favicons mages path
     */
    protected function setFilesLocation($imagePath)
    {
        $this->files_location = array(
            'type' => 'path',
            'path' => $imagePath
        );
    }

    /**
     * Define which type of favicons would be generated
     *
     * @param array $faviconDesign list of favicon design types (from desktop_browser, ios, windows, safari_pinned_tab, coast, open_graph, yandex_browser, firefox_app, android_chrome)
     */
    protected function setFaviconDesign(array $faviconDesign)
    {
        $faviconDesign = array_intersect($this->faviconDesign, $faviconDesign);

        foreach ($faviconDesign as $fd) {
            $this->favicon_design[$fd] = array();
        }
    }

    /**
     * Define additional RealFaviconGenerator settings
     *
     * @param int $compression compression level
     * @param string $scalingAlgorithm scale type
     */
    protected function setSettings($compression = 3, $scalingAlgorithm = self::SCALINGALGORITHMMITCHELL)
    {
        $this->settings = array(
            'compression'              => $compression,
            'scaling_algorithm'        => $scalingAlgorithm,
            'error_on_image_too_small' => true
        );
    }

    /**
     * @param boolean $versioning
     */
    protected function setVersioning($versioning)
    {
        $this->versioning = $versioning ? true : false;
    }

    /**
     * Convert Query object to json string
     *
     * @return string Query json conversion
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
                    'settings'       => $this->settings,
                    'versioning'     => $this->versioning
                )
            )
        );
    }
}