<?php

namespace EdgarEz\FaviconBundle\Generator;

/**
 * Class Generator
 * @package EdgarEz\FaviconBundle\Generator
 */
class Generator
{
    /**
     * @var string RealFaviconGenerator api key
     */
    private $apiKey;

    /**
     * @var string master picture path
     */
    private $masterPircture;

    /**
     * @var string favicon archive path
     */
    private $packageDest;

    /**
     * @var string favicon view
     */
    private $faviconsView;

    /**
     * @var string favicon design
     */
    private $faviconDesign;

    /**
     * @var boolean if versioning
     */
    private $versioning;

    /**
     * @var string baseurl
     */
    private $baseurl;

    /**
     * @var string uri
     */
    private $uri;

    /**
     * apiKey setter
     *
     * @param string $api_key
     */
    public function setApiKey($api_key)
    {
        $this->apiKey = $api_key;
    }

    /**
     * masterPicture setter
     *
     * @param string $master_picture
     */
    public function setMasterPicture($master_picture)
    {
        $this->masterPircture = $master_picture;
    }

    /**
     * packageDest setter
     *
     * @param string $package_dest
     */
    public function setPackageDest($package_dest)
    {
        $this->packageDest = $package_dest;
    }

    /**
     * faviconsView setter
     *
     * @param string $favicons_view
     */
    public function setFaviconsView($favicons_view)
    {
        $this->faviconsView = $favicons_view;
    }

    /**
     * faviconDesign setter
     *
     * @param string $favicon_design
     */
    public function setFaviconDesign($favicon_design)
    {
        $this->faviconDesign = $favicon_design;
    }

    /**
     * versioning setter
     *
     * @param bool $versioning
     */
    public function setVersioning($versioning)
    {
        $this->versioning = $versioning;
    }

    /**
     * baseurl setter
     *
     * @param string $baseurl
     */
    public function setBaseurl($baseurl)
    {
        $this->baseurl = $baseurl;
    }

    /**
     * uri setter
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * apiKey getter
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * masterPicture getter
     *
     * @return string
     */
    public function getMasterPicture()
    {
        return $this->masterPircture;
    }

    /**
     * packageDest getter
     *
     * @return string
     */
    public function getPackageDest()
    {
        return $this->packageDest;
    }

    /**
     * faviconsView getter
     *
     * @return string
     */
    public function getFaviconsView()
    {
        return $this->faviconsView;
    }

    /**
     * faviconDesign getter
     *
     * @return string
     */
    public function getFaviconDesign()
    {
        return $this->faviconDesign;
    }

    /**
     * versioning getter
     *
     * @return boolean
     */
    public function getVersioning()
    {
        return $this->versioning;
    }

    /**
     * baseurl getter
     *
     * @return string
     */
    public function getBaseurl()
    {
        return $this->baseurl;
    }

    /**
     * uri getter
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Call RealFaviconGenerator service and get response
     *
     * @param string $picturePath image base path
     * @param string $imagePath favicons images path
     * @return QueryData response from the RealFaviconGeneration service
     */
    public function generate($picturePath, $imagePath)
    {
        $parameters = array(
            'master_picture_path' => $picturePath,
            'image_path'          => $imagePath,
            'favicon_design'      => $this->faviconDesign,
            'versioning'          => $this->versioning
        );
        $queryData = new QueryData($this->apiKey, $parameters);

        return $queryData;
    }
}
