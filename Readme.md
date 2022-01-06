# Silverstripe Dropzone Field
A [Dropzone.js](https://www.dropzonejs.com/) file upload field for SilverStripe. Use on frond end forms.
![Image of Yaktocat](https://github.com/xddesigners/silverstripe-dropzonefield/blob/master/preview.jpg)

## Installation
Install the module trough composer:
```bash
composer require xddesigners/silverstripe-dropzonefield
``` 

## Usage
```php
XD\DropzoneField\Forms\DropzoneField::create('Image');
```

## Enable frontend editing of image object
```php
DropzoneField::create('Image', _t(__CLASS__ . '.Image', 'Profile image'), $this->Image() )
    ->setFolderName('profile')
    ->setIsMultiUpload(false)
    ->setAddRemoveLinks(true);
```

### Maintainers 
[Bram de Leeuw](https://www.twitter.com/bramdeleeuw)
