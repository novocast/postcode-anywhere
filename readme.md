# A Laravel wrapper for PCA Predict APIs

Use this Laravel wrapper to make easy calls to the [**PCA Predict**](http://www.postcodeanywhere.co.uk/) APIs. It's as simple as a s
Single line of code within your [**Laravel**](http://laravel.com/) application that's all it takes.
Currently this wrapper only makes use of the CapturePlus web service that finds and returns postcode searches.


Dependencies
------------
 * [**PHP cURL**] (http://php.net/manual/en/curl.installation.php)
 * [**Laravel >= 5.2**] (https://laravel.com/docs/5.2)


Installation
------------

To install use the following console comman:
```
composer require novocast/pca-predict:dev-master
```

Or add the following line into your `composer.json` file, and run `composer update` in your console:

```php
"novocast/pca-predict": "dev-master"
```


Configuration
-------------

To start using this module all you need to do is register the Laravel service provider with Laravel. You can find this in your `config/app.php` file:

```php
'providers' => [
	...
    Novocast\PostCodeAnywhere\PostCodeAnywhereServiceProvider::class,
    ...
]

'aliases' => [
	...
    'PostCodeAnywhere' => Novocast\PostCodeAnywhere\PostCodeAnywhereFacade::class,
    ...
]
```

Publish your configuration file:

```php 
    php artisan vendor:publish
``` 

Finally, replace the required Key with your own, as `PCA_KEY` in your `.env` file.


How to Use
-----
### CapturePlus ###
It's easy to find or retreive a post code using PostCodeAnywhere, and usually only takes a line of code.

Within your application call `\PostCodeAnywhere::find( $params )` or `\PostCodeAnywhere::retreive( $params )` with array of parameters.

Example:

```php
$params = ['SearchTerm'=>'123 Main Street', 'Endpoint'=>'json', 'Service' => 'CapturePlus'];
```

```php
$response = \PostCodeAnywhere::find($params);
```

Or as a retrieve request:

```php
$params = ['Id'=>'GBR|1234567', 'Endpoint'=>'json', 'Service' => 'CapturePlus'];
```

```php
$response = \PostCodeAnywhere::retrieve($params);
```

You can review PCA Predicts [***API documentation***](http://www.postcodeanywhere.co.uk/support/webservice/postcodeanywhere/interactive/findbypostcode/1/) for the necessary parameters, as well as for more information on the find and retrieve paradigm used by the Capture .

