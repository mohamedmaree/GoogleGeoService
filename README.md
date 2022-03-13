# google-geo-services
## Installation

You can install the package via [Composer](https://getcomposer.org).

```bash
composer require maree/google-geo-services
```
Publish your google-geo-services config file with

```bash
php artisan vendor:publish --provider="maree\googleGeoServices\googleGeoServiceProvider" --tag="google-geo-services"
```
then change your google api key config from config/google-geo-services.php file
## Usage
get direct line between two points not 'road or way' but 'direct line'
distance return in KM
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::directDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);  


```


## current available services :
- directDistance
- DrivingDistance








