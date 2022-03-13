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

## nearGooglePlaces
- To get near google places from specified point 
- you can define category by pass the value in $category key EX : 'Restaurant or Store'
- you can define place by pass the value in $category key EX : 'kfc or Macdonalds'
- must insert valid google key in config/google-geo-services.php to return valid result
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::nearGooglePlaces($latitude='', $longitude='',$category='',$lang='ar',$next_page_token='');  


```
- note : you can get next page of array of places by pass the value of 'next_page_token' return in response.
- note : that api cost alot of money from google places api so that if you return the same places or area every time
- run that service
- save response places in local database 
- in next time check first if places exists in local databse use it else if that new area run the service and save places in database to use it next time

## directDistance
- directDistance
- To get direct line between two points not 'road or way' but 'direct line'
- distance return in KM
- note: doesn't use google key here
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::directDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);  


```

## getAddressBylatlng
- To get address by coordinates 
- must insert valid google key in config/google-geo-services.php to return valid result
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::getAddressBylatlng($lat = '' ,$long = '', $lang = 'ar');  


```

## getCityBylatlng
- To get City short name by coordinates 
- must insert valid google key in config/google-geo-services.php to return valid result
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::getCityBylatlng($lat = '' ,$long = '', $lang = 'ar');  


```

## GetDrivingDistance
- To get shortest road distance and time between two points 
- must insert valid google key in config/google-geo-services.php to return valid result
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::GetDrivingDistance($lat1='', $long1='',$lat2='', $long2='',$lang ='ar' ,$mode='driving');  


```
- note :available modes driving|walking|bicycling|transit

## GetPathAndDirections
- To get road distance and time between two points when define points in that road
- must insert valid google key in config/google-geo-services.php to return valid result
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::GetPathAndDirections($lat1='', $long1='',$lat2='', $long2='' ,$path='',$lang ='ar',$mode='driving');  


```

- note :available modes driving|walking|bicycling|transit
- note : add path as string with '|' between points like $path = '31.0345612,31.3489804|31.0328805,31.36542648'

## currentCountry
- To get current country by request IP address not geolocation no need for google api key
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::currentCountry();  


```

## convertCurrency
- To convert amount of money from currency to another
- must insert valid currency converter api key in config/google-geo-services.php to return valid result 
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::convertCurrency($amount =10 ,$from_currency='USD',$to_currency='EGP');  


```

## praytime
- To get muslims pray times by area geolocation no need for google api key
```php
use maree\googleGeoServices\GoogleGeoService;

GoogleGeoService::praytime($from_lat='',$from_long='');  


```

## current available services :
- nearGooglePlaces
- directDistance
- getAddressBylatlng
- getCityBylatlng
- GetDrivingDistance
- GetPathAndDirections
- currentCountry
- convertCurrency
- praytime








