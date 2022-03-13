<?php
namespace maree\googleGeoServices;

class GoogleGeoService {

  public static function directDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo){
      $earthRadius = 6371000;
      // convert from degrees to radians
      $latFrom = deg2rad(doubleval( $latitudeFrom) );
      $lonFrom = deg2rad(doubleval( $longitudeFrom) );
      $latTo   = deg2rad(doubleval( $latitudeTo) );
      $lonTo   = deg2rad(doubleval( $longitudeTo) );

      $lonDelta = $lonTo - $lonFrom;
      $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
      $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
      $angle = atan2(sqrt($a), $b);
      $in_km = ($angle * $earthRadius) / 1000 ;
      return round($in_km, 2);
  }

   
}