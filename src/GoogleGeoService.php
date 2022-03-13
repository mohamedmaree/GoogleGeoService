<?php
namespace maree\googleGeoServices;

class GoogleGeoService {

    public static function nearGooglePlaces($latitude='', $longitude='',$category='',$lang='ar',$next_page_token=''){
        $google_key = config('google-geo-services.google_key');
        $next_page_token = ($next_page_token == '' )?'':'&pagetoken='.$next_page_token;
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$latitude.",".$longitude."&name=".$category."&rankby=distance&key=".$google_key."&language=".$lang.$next_page_token;
        $jsonresult = file_get_contents($url);
        $results    = json_decode($jsonresult);
        $places = [];
        if($results->results){
            foreach($results->results as $result){
                      $places[] = [  'name'            => $result->name,
                                     'lat'             => $result->geometry->location->lat,
                                     'lng'             => $result->geometry->location->lng,
                                     'icon'            => $result->icon,
                                     'place_id'        => $result->place_id,
                                     'reference'       => $result->reference,
                                     'vicinity'        => $result->vicinity,
                                     'rating'          => ($result->rating)??0.0,
                                     'user_ratings_total' => ($result->user_ratings_total)??0,
                                ];                    
                                               
            }
            $next_page_token = (isset($results->next_page_token))?$results->next_page_token:'';
        }
        return ['places' => $places , 'next_page_token' => $next_page_token];
    }

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

    public static function getAddressBylatlng($lat = '' ,$long = '', $lang = 'ar'){
        $google_key = config('google-geo-services.google_key');
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

    public static function getCityBylatlng($lat = '' ,$long = '', $lang = 'ar'){
        $google_key = config('google-geo-services.google_key');
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

    public static function GetDrivingDistance($lat1='', $long1='',$lat2='', $long2='',$lang ='ar' ,$mode='driving'){
        $google_key = config('google-geo-services.google_key');
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=".$mode."&language=".$lang."&key=".$google_key;
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
        $in_kms = ($distance / 1000); //in kms 
        $in_kms = round($in_kms, 2);
        return ['distance' => $in_kms , 'time' => $time , 'time_text'=>$time_text];
    }

    public static function GetPathAndDirections($lat1='', $long1='',$lat2='', $long2='' ,$path='',$lang ='ar',$mode='driving'){
        $google_key = config('google-geo-services.google_key');
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$lat1.",".$long1."&destination=".$lat2.",".$long2."&waypoints=".$path."&mode=".$mode."&language=".$lang."&key=".$google_key;
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        $distance = 0; 
        $time     = 0;
        if(isset($response['routes'])){
            foreach($response['routes'][0]['legs'] as $road){
               $distance += $road['distance']['value'];
               $time     += $road['duration']['value'];
            }    
        }else{
            $distance = directDistance($lat1, $long1, $lat2, $long2);
            $distance = $distance * 1000;  // in Meter
            $time     = intval($distance * 1.2).' '.trans('order.minute') ; 
        }        
        $in_kms = ($distance / 1000); //in kms 
        $in_kms = round($in_kms, 2);

        return ['distance' => $in_kms , 'time' => $time ];
    }

    public static function currentCountry(){
        $ip = '';
        if(isset($_SERVER['REMOTE_ADDR'])){
          $ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
        }
        $data = array(  'iso'      => '',          // EG
                        'name'     => '',//"Egypt"
                        'currency' => '',   //"EGP"
                        'symbol'   => '',    // "£"
                        'ratio'    => '', //to USD  "17.3873"
                        'time_zone'=> '' //'Africa/cairo'
                      );
        
        $url = "http://www.geoplugin.net/json.gp?ip=".$ip;
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
        return  $data;
    }

    public static function convertCurrency($amount,$from_currency,$to_currency){
        $apikey        = config('google-geo-services.currencyconverterapi');
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

    public static function praytime($from_lat='',$from_long=''){
            $praytimes = [];
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
                  } 
                }
              }
        return $praytimes;   
    }
}