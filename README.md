# EdgarEzFaviconBundle

> Integrate generation of a multiplatform favicon with [RealFaviconGenerator](http://realfavicongenerator.net/) into your eZPlatform application.
> Inspired by RealFaviconGeneratorBundle by joaoalves89 (https://github.com/joaoalves89/RealFaviconGeneratorBundle/tree/master)


## Installation

### Get the bundle using composer

Add EdgarEzFaviconBundle by running this command from the terminal at the root of
your eZPlatform project:

```bash
composer require edgarez/faviconbundle
```


### Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// ezpublish/EzPublishKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new EdgarEz\FaviconBundle\EdgarEzFaviconBundle(),
        // ...
    );
}
```

### Configure bundle

```yaml
# ezpublish/config/config.yml
edgar_ez_favicon:
    system:
        acme_group: #for each siteaccess
            api_key: #required
            master_picture: @AcmeBundle/Resources/public/images/photo.jpg #required
            package_dest: @AcmeBundle/Resources/public/images/favicons/ #required
            favicons_view: @AcmeBundle/Resources/views/favicons.html.twig #required
            favicon_design: ["ios", "desktop_browser", "windows"] #required
            versioning: true
        acme: #or/and especially for acme siteaccess
            api_key: #required
            master_picture: @AcmeBundle/Resources/public/images/acme/photo.jpg #required
            package_dest: @AcmeBundle/Resources/public/images/acme/favicons/ #required
            favicons_view: @AcmeBundle/Resources/views/acme/favicons.html.twig #required
            favicon_design: ["ios", "desktop_browser", "windows"] #required    
            versioning: true
```

* api_key : visit RealFaviconGenerator website to obtain your own API Key for Non-interactive mode
* master_picture : define path of image model used to generate favicons
* package_dest : define were favicon images would be uploaded
* favicons_view : define which twig template would be used to be completed with all head links favicons
* favicon_design : list of client type (from 'desktop_browser', 'ios', 'windows', 'safari_pinned_tab', 'coast', 'open_graph', 'yandex_browser', 'firefox_app', 'android_chrome')
* versioning : define if GET parameter would be adder after favicons path

### How to use

```command
// generate favicons for global configuration (acme_group)
php ezpublish/console edgar_ez:favicon

// generate favicons only for acme siteaccess
php ezpublish/console edgar_ez:favicon --siteaccess=demo

// install favicons uploaded in your AcmeBundle
php ezpublish/console asset:install
```

### This bundle is still in beta version!