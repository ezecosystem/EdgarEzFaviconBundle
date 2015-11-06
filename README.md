# EdgarEzFaviconBundle

[![Latest Stable Version](https://poser.pugx.org/edgarez/faviconbundle/v/stable)](https://packagist.org/packages/edgarez/faviconbundle) 
[![Total Downloads](https://poser.pugx.org/edgarez/faviconbundle/downloads)](https://packagist.org/packages/edgarez/faviconbundle)
[![Daily Downloads](https://poser.pugx.org/edgarez/faviconbundle/d/daily)](https://packagist.org/packages/edgarez/faviconbundle)
[![Latest Unstable Version](https://poser.pugx.org/edgarez/faviconbundle/v/unstable)](https://packagist.org/packages/edgarez/faviconbundle) 
[![License](https://poser.pugx.org/edgarez/faviconbundle/license)](https://packagist.org/packages/edgarez/faviconbundle)

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
            api_key: ... #required
            master_picture: @AcmeBundle/Resources/public/images/acme/photo.jpg #required
            package_dest: @AcmeBundle/Resources/public/images/acme/favicons/ #required
            favicons_view: @AcmeBundle/Resources/views/acme/favicons.html.twig #required  
            versioning: true
            favicon_design:
                desktop_browser: []
                ios:
                    picture_aspect: "background_and_margin"
                    margin: 0
                    background_color: "#fff"
                windows:
                    picture_aspect: "white_silhouette"
                    background_color: "#fff"
                firefox_app:
                    picture_aspect: "circle"
                    keep_picture_in_circle: true
                    circle_inner_margin: 5
                    background_color: "#fff"
                    manifest:
                        app_name: "bar"
                        app_description: "bar description"
                android_chrome:
                    picture_aspect: "shadow"
                    theme_color: "#fff"
                    manifest:
                        name: "bar"
                        display: "standalone"
                        orientation: "portrait"
                safari_pinned_tab:
                    picture_aspect: "black_and_white"
                    threshold: 60
                    theme_color: "#fff"
                coast:
                    picture_aspect: "background_and_margin"
                    background_color: "#fff"
                    margin: 4
                open_graph:
                    picture_aspect: "background_and_margin"
                    background_color: "#fff"
                    margin: 4
                    ratio: "1.91:1"
                yandex_browser:
                    background_color: "#fff"
                    manifest:
                        show_title: true
                        version: "1.0"
        acme: #or/and especially for acme siteaccess
            # ... same as before
```

* api_key : visit RealFaviconGenerator website to obtain your own API Key for Non-interactive mode
* master_picture : define path of image model used to generate favicons
* package_dest : define were favicon images would be uploaded
* favicons_view : define which twig template would be used to be completed with all head links favicons
* versioning : define if GET parameter would be adder after favicons path
* favicon_design : all parameters are not implemented, see documentation (http://realfavicongenerator.net/api/non_interactive_api#.VhrCqnrtlBc)

### How to use

```command
// generate favicons for global configuration (acme_group)
php ezpublish/console edgar_ez:favicon

// generate favicons only for acme siteaccess
php ezpublish/console edgar_ez:favicon --siteaccess=demo

// install favicons uploaded in your AcmeBundle
php ezpublish/console asset:install
```
