<?php

namespace EdgarEz\FaviconBundle\Command;

use EdgarEz\FaviconBundle\Generator\QueryData;
use Guzzle\Http\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FaviconCommand
 * @package EdgarEz\FaviconBundle\Command
 */
class FaviconCommand extends ContainerAwareCommand
{
    /**
     * success service constant
     */
    const STATUSSUCCESS = 'success';

    /**
     * @var EdgarEz\FaviconBundle\Generator\Generator
     */
    protected $generator;

    /**
     * @var string picture path
     */
    protected $picturePath;

    /**
     * @var string archive file location
     */
    protected $fileLocation;
    /**
     * @var string image path
     */
    protected $imagePath;

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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generator = $this->getContainer()->get('edgar_ez_favicon.generator');

        $kernel              = $this->getContainer()->get('kernel');
        $this->picturePath   = $kernel->locateResource($this->generator->getMasterPicture());
        $this->fileLocation  = $kernel->locateResource($this->generator->getPackageDest());

        $packageDest = explode('/', trim($this->generator->getPackageDest(), '@'));
        $bundleName  = str_replace('bundle', '', strtolower($packageDest[0]));
        unset($packageDest[0]);
        $this->imagePath = '/bundles/' . $bundleName . '/' . trim(str_replace('Resources/public', '', implode('/', $packageDest)), '/') . '/';

        $queryData = $this->generator->generate(
            $this->picturePath,
            $this->imagePath
        );
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
        $client   = new Client($this->generator->getBaseurl());
        $request  = $client->post($this->generator->getUri(), null, $queryData->__toString());
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
        }

        return false;
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

        $htmlContent = explode("\n", $htmlContent);
        $pattern1     = '/href="([^"]*)"/';
        $pattern2     = '/content="([^"]*)"/';
        $replacement1 = 'href="{{ asset(\'%s\') }}"';
        $replacement2 = 'content="{{ asset(\'%s\') }}"';

        $htmlResult = array();
        foreach ($htmlContent as $line) {
            $replacement = false;
            $pattern     = false;
            preg_match($pattern1, $line, $matches);
            if (isset($matches[1])) {
                $replacement = $replacement1;
                $pattern     = $pattern1;
            } else {
                preg_match($pattern2, $line, $matches);
                if (isset($matches[1])) {
                    $replacement = $replacement2;
                    $pattern     = $pattern2;
                } else {
                    $htmlResult[] = $line;
                }
            }

            if ($replacement) {
                $imageInfo = pathinfo($matches[1]);
                if (isset($imageInfo['extension'])) {
                    $htmlResult[] = preg_replace($pattern, sprintf($replacement, $matches[1], $line), $line);
                } else {
                    $htmlResult[] = $line;
                }
            }
        }

        $kernel = $this->getContainer()->get('kernel');

        $fp = fopen($kernel->locateResource($this->generator->getFaviconsView()), 'w');
        fwrite($fp, implode("\n", $htmlResult));
        fclose($fp);
    }
}
