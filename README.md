# XMAssetVersionBundle
Provides a asset version strategy based on the file modification time for the
Symfony Asset Component.

This only works if the web accessible files are stored locally.

## Installation

### Step 1: Download the Bundle

**This package is not on Packagist, so the repository will need to be added
manually in composer.json**

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ php composer.phar require xm/asset-version
```

This command requires [Composer](https://getcomposer.org/download/).

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new XM\AssetVersionBundle\XMAssetVersionBundle(),
        );

        // ...
    }
}
```

### Step 3: Add Service

Create a service for the version strategy:

```yml
    assets.version_strategy:
        class: XM\AssetVersionBundle\Asset\VersionStrategy\FileModifiedTimestampVersionStrategy
        arguments: ['%web_root%']
```

`%web_root%` is the root of the html dir. This will be added to the paths passed
to the version strategy. It could be configured as
`parameter.web_root: '%kernel.root_dir%/../html'` where `html` is the root of
the web accessible directory.

### Step 4: Set the `version_strategy` for Symfony Asset

In `config.yml` add/set:

```yml
framework:
    assets:
        version_strategy: assets.version_strategy
```

### Step 5: Add rewrite to Apache config or .htaccess

Add something similar to the following to your Apache site config or `.htaccess`
file. This will allow for versioning of CSS, JS, image and pdf files.

```
<IfModule mod_rewrite.c>
    RewriteEngine On

    # If the requested filename exists, simply serve it.
    # We only want to let Apache serve files and not directories.
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^ - [L]

    # Filename-based cache busting
    # Apply to CSS, JS, image and PDF files
    # Uses the format /version-4.3/css/public.css (version can be 4.3, 4, or a timestamp)
    RewriteRule ^version-([\d\.]+)/(.+)\.(css|cur|gif|ico|jpe?g|js|png|svgz?|webp|webmanifest|pdf|map)$ $2.$3 [L]
</IfModule>
```