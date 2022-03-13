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

    function getAddressBylatlng($lat = '' ,$long = '', $lang = 'ar'){
        $google_key = setting('google_places_key');
        $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=$google_key&language=$lang";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geocode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($response);
        $dataarray = get_object_vars($output);
        if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
            if (isset($dataarray['results'][0]->formatted_address)) {
                $address = $dataarray['results'][0]->formatted_address;
            } else {
                $address = '';
            }
        } else {
            $address = '';
        }
        return $address;
    }

    function getCityBylatlng($lat = '' ,$long = '', $lang = 'ar'){
        $google_key = setting('google_places_key');
        $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=$google_key&language=$lang";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geocode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($response);
        $dataarray = get_object_vars($output);

        if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
            if(isset($dataarray['results'][0]->address_components)){            
                
                if(isset($dataarray['results'][0]->address_components[1])){
                    if($dataarray['results'][0]->address_components[1]->types[0] == 'locality' || $dataarray['results'][0]->address_components[1]->types[0] == 'administrative_area_level_1' ){
                       return $dataarray['results'][0]->address_components[1]->short_name;
                    }
                } 

                if(isset($dataarray['results'][0]->address_components[2])){
                    if($dataarray['results'][0]->address_components[2]->types[0] == 'locality' || $dataarray['results'][0]->address_components[2]->types[0] == 'administrative_area_level_1' ){
                       return $dataarray['results'][0]->address_components[2]->short_name;
                    }
                } 
                if(isset($dataarray['results'][0]->address_components[3])){
                  if($dataarray['results'][0]->address_components[3]->types[0] == 'locality' || $dataarray['results'][0]->address_components[3]->types[0] == 'administrative_area_level_1' ){
                     return $dataarray['results'][0]->address_components[3]->short_name;
                  }
                }
                if(isset($dataarray['results'][0]->address_components[4])){
                  if($dataarray['results'][0]->address_components[4]->types[0] == 'locality' || $dataarray['results'][0]->address_components[4]->types[0] == 'administrative_area_level_1' ){
                     return $dataarray['results'][0]->address_components[4]->short_name;
                  }
                }
                if(isset($dataarray['results'][0]->address_components[5])){
                  if($dataarray['results'][0]->address_components[5]->types[0] == 'locality' || $dataarray['results'][0]->address_components[5]->types[0] == 'administrative_area_level_1' ){
                     return $dataarray['results'][0]->address_components[5]->short_name;
                  }
                }            
            }else{
                $short_name = '';
            }
        }else {
            $short_name = '';
        }
        return $short_name;
    }

function GetDrivingDistance($lat1='', $long1='',$lat2='', $long2='' ,$lang ='ar'){
    $google_key = setting('google_places_key');
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=".$lang."&key=".$google_key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($result, true);
    $time_text = '';
    if($response['rows']){
      if($response['rows'][0]['elements'][0]['status'] == 'ZERO_RESULTS' || ($response['rows'][0]['elements'][0]['status'] == 'NOT_FOUND') ){
        $distance = directDistance($lat1, $long1, $lat2, $long2);
        $time     = ceil($distance * 2).' mins' ;
        $time_text = $time; 
        $distance = $distance * 1000; // in meter
      }else{
        $distance = $response['rows'][0]['elements'][0]['distance']['value'];  // in Meter
        $time_text     = $response['rows'][0]['elements'][0]['duration']['text'];  //in seconds     
        $time          = intval(intval($response['rows'][0]['elements'][0]['duration']['value']) / 60) ;  //in seconds 
        $time          = ($time <= 0)? 1 : $time;     
      }
    }else{
        $distance = directDistance($lat1, $long1, $lat2, $long2);
        $time     = ceil($distance * 2).' mins' ; 
        $time_text = $time; 
        $distance = $distance * 1000;  // in Meter
    }        
    //in text format
    // $distance = $response['rows'][0]['elements'][0]['distance']['text']; 
    // $time     = $response['rows'][0]['elements'][0]['duration']['text']; 
    $in_kms = ($distance / 1000); //in kms 
    $in_kms = round($in_kms, 2);

    return ['distance' => $in_kms , 'time' => $time , 'time_text'=>$time_text];
}

function GetPathAndDirections($lat1='', $long1='',$lat2='', $long2='' ,$path='',$lang ='ar'){
    // $path = '31.0345612,31.3489804|31.0328805,31.36542648';
    //https://maps.googleapis.com/maps/api/directions/json?origin=31.0345612,31.3489804&destination=31.0034004,31.3730575&waypoints=31.0328805,31.36542648&mode=driving&language=ar&key=AIzaSyDYjCVA8YFhqN2pGiW4I8BCwhlxThs1Lc0
    $google_key = setting('google_places_key');
    $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$lat1.",".$long1."&destination=".$lat2.",".$long2."&waypoints=".$path."&mode=driving&language=".$lang."&key=".$google_key;
    $ch  = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($result, true);
    // routes->0->legs->
    //                [0,1,..]->
    //                          distance&duration&end_location->lat&lng
    $distance = 0; 
    $time     = 0;
    if($response['routes']){
        foreach($response['routes'][0]['legs'] as $road){
           $distance += $road['distance']['value'];
           // $time     += $road['duration']['value'];
        }    
    }else{
        $distance = directDistance($lat1, $long1, $lat2, $long2);
        $distance = $distance * 1000;  // in Meter
        // $time     = intval($distance * 1.2).' '.trans('order.minute') ; 
    }        
    $in_kms = ($distance / 1000); //in kms 
    $in_kms = round($in_kms, 2);

    return $in_kms;
}

    function currentCountry(){
        $ip = '';
        if(isset($_SERVER['REMOTE_ADDR'])){
          $ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
        }
        $data = array(  'iso'      => 'SA',          // EG
                        'name'     => 'saudi arabia',//"Egypt"
                        'currency' => 'SAR',   //"EGP"
                        'symbol'   => 'SR',    // "£"
                        'ratio'    => '3.750', //to USD  "17.3873"
                        'time_zone'=> 'Asia/Riyadh'
                      );
        $url = "http://www.geoplugin.net/json.gp?ip=".$ip;
          // if(is_readable($url)){
            $geoplugin = @file_get_contents($url,true);
            if($geoplugin === FALSE){
                return $data;
            }else{
              $dataArray = json_decode($geoplugin);
              if($dataArray){
                $data = array('iso'      => $dataArray->geoplugin_countryCode,    // EG
                              'name'     => $dataArray->geoplugin_countryName,    //"Egypt"
                              'currency' => $dataArray->geoplugin_currencyCode,   //"EGP"
                              'symbol'   => $dataArray->geoplugin_currencySymbol, // "£"
                              'ratio'    => $dataArray->geoplugin_currencyConverter, //to USD  "17.3873"
                              'time_zone'=> $dataArray->geoplugin_timezone
                            );
              }
            }       
          // }
        return  $data;
    }

    function convertCurrency($amount,$from_currency,$to_currency){
        $apikey        = setting('currencyconverterapi');
        $from_Currency = urlencode($from_currency);
        $to_Currency   = urlencode($to_currency);
        $rate = 1;

        if(strtoupper($from_Currency) != strtoupper($to_Currency) ){
          $query         = "{$from_Currency}_{$to_Currency}";
          $url = @file_get_contents("http://api.currencyconverterapi.com/api/v6/convert?q={$query}&compact=ultra&apiKey={$apikey}");
          if($url === false) {
            $countries = $from_currency.'_'.$to_currency;
            $json = @file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q='.$countries.'&compact=ultra');
          }else{
            $json = @file_get_contents("http://api.currencyconverterapi.com/api/v6/convert?q={$query}&compact=ultra&apiKey={$apikey}");
          }
          $obj   = json_decode($json, true);
          $rate  = (isset($obj["$query"]) )? floatval($obj["$query"]) : 1;
        }
        $total = $rate * $amount;
        return number_format($total, 2, '.', '');
    } 

    function praytime($from_lat='',$from_long='',$lang='ar'){
            $praytimes = []; $msg = '';
            $url="http://api.aladhan.com/v1/calendar?latitude=".$from_lat."&longitude=".$from_long."&method=4&month=".date("m")."&year=".date("Y");
              $jsonresult = @file_get_contents($url,true);
              if($jsonresult === FALSE){
                return '';
              }else{
                if($results    = json_decode($jsonresult)){        
                  if(isset($results->data)){
                    $currentday = intval(date('d'))-1 ;
                    $praytimes['Fajr']    = $results->data[$currentday]->timings->Fajr;
                    $praytimes['Dhuhr']   = $results->data[$currentday]->timings->Dhuhr;
                    $praytimes['Asr']     = $results->data[$currentday]->timings->Asr;
                    $praytimes['Maghrib'] = $results->data[$currentday]->timings->Maghrib;
                    $praytimes['Isha']    = $results->data[$currentday]->timings->Isha;
                    foreach($praytimes as $key=>$value){
                      $praytime  = substr($value, 0, 5);
                      $to_time   = strtotime(date("Y-m-d")." ".$praytime);
                      $from_time = strtotime(date('Y-m-d H:i'));
                      $minutes   = intval( round( ($to_time - $from_time) / 60,2) );
                      if(($minutes <= 30) && ($minutes >= 0) ){
                        $msg = setting('pray_msg_'.$lang);
                      }
                    } 
                  } 
                }
              }
        return $msg;   
    }
}