# Unexpected Webp

Magento2 module for images conversion to webp format.

## Requirements

* PHP >= **7.1**
* Cwebp >= **0.5.2**
* libvips >= **8.4.5**
* Magento >= **2.3.2**

## Installation

1. Webp support

    ```shell script
    sudo apt-get install libwebp-dev
    sudo apt-get install webp
    ```
   
   You must also enable GD-support.
   
   Make sure that You have this extension.
   
    ```shell script
   sudo apt-get install phpX-gd
   ```
   
   Where x is PHP version (i. e. 7.1).
   
   Next configure PHP to enable support for webp format.
   
    ```shell script
    --with-webp-dir=DIR
    ```   
   
2. Vips extension

    ```shell script
    sudo apt-get install libvips-dev
    
    pecl install vips
    ```
    
    Add `extension=vips.so` to **php.ini** file.

3. Module

    ```php
    composer require unexpected/webp
    
    php bin/magento module:enable unexpected/webp
    
    php bin/magento setup:upgrade
    
    php bin/magento setup:di:compile
    
    php bin/magento setup:static-content:deploy -f
    ```

## Usage

#### **Stores->Configuration->Unexpected->Webp**

* **General->Enabled** - module activation
* **Settings->Algorithm** - choose one from three method types 
* **Settings->Quality** - quality of converted image
* **Conversion->Convert images on product save** - if selected,
 after uploaded images on product page and save product, images will be converted automatically
 
#### **Content->Conversion**

**Convert now** 

On left side select catalogs in which images are to be converted.

On right side in **Conversion** tab determine extensions which images are to be converted.

Click **Convert now** button to start conversion.

**Cron**

On left side select catalogs in which images are to be converted.

On right side in **Cron** tab click **Enable** toggle to schedule convert in cron.
 
Determine cron frequency, time and extensions which images are to be converted.

**Clear**

Click **Clear all** button in **Clear** tab to remove all webp images. 

**Save**

Click Save to remember settings also You have to clear cache after this.

#### Catalog->Products

Upload images in **Images And Videos** tab.

Update changes by click on **Save** button.

All images assigned to product should be converted.

## Sources

https://www.php.net/manual/en/image.installation.php

https://developers.google.com/speed/webp

https://github.com/libvips/libvips

https://github.com/libvips/php-vips