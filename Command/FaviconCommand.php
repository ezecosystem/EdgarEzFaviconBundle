<?php

namespace EdgarEz\FaviconBundle\Command;

use EdgarEz\FaviconBundle\Generator\QueryData;
use Guzzle\Http\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FaviconCommand extends ContainerAwareCommand
{
    const STATUSSUCCESS = 'success';

    protected $apiKey;
    protected $masterPicture;
    protected $packageDest;
    protected $faviconsView;

    protected $picturePath;
    protected $fileLocation;

    /**
     * Configure Favicon Symfony command
     */
    protected function configure()
    {
        $this
            ->setName('edgar_ez:favicon')
            ->setDescription('Favicon Generator');
    }

    /**
     * Execute Favicon Symfony command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configResolver       = $this->getContainer()->get('ezpublish.config.resolver');
        $this->apiKey         = $configResolver->getParameter('api_key', 'edgar_ez_favicon');
        $this->masterPicture  = $configResolver->getParameter('master_picture', 'edgar_ez_favicon');
        $this->packageDest    = $configResolver->getParameter('package_dest', 'edgar_ez_favicon');
        $this->faviconsView   = $configResolver->getParameter('favicons_view', 'edgar_ez_favicon');

        $kernel             = $this->getContainer()->get('kernel');
        $this->picturePath  = $kernel->locateResource($this->masterPicture);
        $this->fileLocation = $kernel->locateResource($this->packageDest);
        $this->faviconsView = $kernel->locateResource($this->faviconsView);

        $generator = $this->getContainer()->get('edgar_ez_favicon.generator');
        $queryData = $generator->generate($this->apiKey, $this->picturePath, $this->fileLocation);
        $output->writeln('RealFaviconGenerator query generated');

        $response    = $this->getResponse($queryData);
        $packageFile = $this->uploadIcons($response->getBody(true));
        $output->writeln('Favicons image uploaded');

        if ($packageFile) {
            $this->unpackIcons($packageFile);
            $output->writeln('Favicons image stored');

            $this->createView($response->getBody(true));
            $output->writeln('favicons view generated');
        }

        $output->writeln('please, execute asset:install command');
    }

    /**
     * Get RealFaviconGenerator response
     *
     * @param QueryData $queryData RealFaviconGenerator query
     * @return mixed RealFaviconGenerator response
     */
    protected function getResponse(QueryData $queryData)
    {
        $configResolver = $this->getContainer()->get('ezpublish.config.resolver');
        $baseUrl        = $configResolver->getParameter('baseurl', 'edgar_ez_favicon');
        $uri            = $configResolver->getParameter('uri', 'edgar_ez_favicon');

        $client   = new Client($baseUrl);
        $request  = $client->post($uri, null, $queryData->__toString());
        $response = $client->send($request);

        return $response;
    }

    /**
     * Upload favicons archive retrieved from RealFaviconGenerator service
     *
     * @param string $content json response
     * @return bool|string false if response status is on error, favicons archive path otherwise
     */
    protected function uploadIcons($content)
    {
        $content = json_decode($content);

        if (isset($content->favicon_generation_result->result->status)
            && $content->favicon_generation_result->result->status == self::STATUSSUCCESS
        ) {
            $packageUrl = $content->favicon_generation_result->favicon->package_url;
            $content    = file_get_contents($packageUrl);

            $packageInfo = pathinfo(parse_url($packageUrl, PHP_URL_PATH));
            $packageName = $packageInfo['filename'];
            $packageFile = $this->fileLocation . $packageName . '.' . $packageInfo['extension'];

            $fp = fopen($packageFile, 'w');
            if ($fp) {
                fwrite($fp, $content);
                return $packageFile;
            }

            return false;
        }
    }

    /**
     * Extract and save favicons images
     *
     * @param strubg $packageFile favicons archive path
     */
    protected function unpackIcons($packageFile)
    {
        $zip = new \ZipArchive();
        if ($zip->open($packageFile) === true) {
            $zip->extractTo($this->fileLocation);
            $zip->close();
        }
    }

    /**
     * Create favicon twig template based on RealFaviconGenerator html response
     *
     * @param string $content
     */
    protected function createView($content)
    {
        $content = json_decode($content);

        $htmlContent = $content->favicon_generation_result->favicon->html_code;

        $htmlContent = explode( "\n", $htmlContent);
        $htmlResult  = array();
        foreach($htmlContent as $key => $line)
        {
            preg_match('/< *link[^>]*href *= *["\']?([^"\']*)/i', $line, $matches);
            if (isset($matches[1])) {
                $imageInfo = pathinfo($matches[0]);
                $imageName = $imageInfo['filename'] . '.' . $imageInfo['extension'];
                $packageDest = explode('/', trim($this->packageDest, '@'));
                $bundleName = str_replace('bundle', '', strtolower($packageDest[0]));
                unset($packageDest[0]);
                $imagePath = 'bundles/' . $bundleName . '/' . trim(str_replace('Resources/public', '', implode('/', $packageDest)), '/') . '/' . $imageName;

                $pattern = '/< *link[^>]*href *= *["\']?([^"\']*)/i';
                $replace = '<link href="{{ asset(\'' . $imagePath . '\') }}';
                $line = preg_replace($pattern, $replace, $htmlContent[$key]);
                $htmlResult[] = $line;
            } else {
                $htmlResult[] = $htmlContent[$key];
            }
        }

        $fp = fopen($this->faviconsView, 'w');
        fwrite($fp, implode("\n", $htmlResult));
        fclose($fp);
    }
}
