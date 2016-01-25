<?php
/**
 * Sentiment Analysis 2.0 starting client for PHP.
 *
 * In order to run this example, the license key must be included in the key variable.
 * If you don't know your key, check your personal area at MeaningCloud (https://www.meaningcloud.com/developer/account/licenses)
 *
 * Once you have the key, edit the parameters and call "php sentimentclient-2.0.php"
 *
 * You can find more information at http://www.meaningcloud.com/developer/sentiment-analysis/doc/2.0
 *
 * @author     MeaningCloud
 * @contact    http://www.meaningcloud.com 
 * @copyright  Copyright (c) 2015, MeaningCloud LLC All rights reserved.
 */

$api = 'http://api.meaningcloud.com/sentiment-2.0';
$key = '167884a44a359889e003b50c2e90364d';
$txt = "I hate it :(";
$model = 'general_es';  // general_en / general_es / general_fr 

// We make the request and parse the response to an array
$response = sendPost($api, $key, $model, $txt);
$json = json_decode($response, true);

// Show the response
echo "Response:\n";
echo "==============\n";
echo $response;
echo "\n\n";

// Prints the specific fields in the response (sentiment)
echo "Sentiment: \n";
echo "============\n";
if(isset($json['score_tag'])) {
  echo 'Global sentiment: '.$json['score_tag'].' ('.$json['agreement'].")\n";
  echo 'Subjectivity: '.$json['subjectivity']."\n";
  echo 'Irony: '.$json['irony']."\n";
  echo 'Confidence: '.$json['confidence']."\n";
} else
  echo "Not found\n";

if(isset($json['sentimented_entity_list']) && count($json['sentimented_entity_list'])>0) {
  echo "\nEntities:\n";
  echo "============\n";
  foreach($json['sentimented_entity_list'] as $e) {
    echo '  - '.$e['form'];
    if(isset($e['type'])) {
      echo ' ('.$e['type'].')';
    }
    echo "\n";
  }
}
if(isset($json['sentimented_concept_list']) && count($json['sentimented_concept_list'])>0) {
  echo "\nConcepts:\n";
  echo "============\n";
  foreach($json['sentimented_concept_list'] as $c) {
    echo '  - '.$c['form'];
    if(isset($c['type'])) {
      echo ' ('.$c['type'].')';
    }
    echo "\n";
  }
}

echo "\n";


// Auxiliary function to make a post request 
function sendPost($api, $key, $model, $txt) {
  $data = http_build_query(array('key'=>$key,
                                 'model'=>$model,
                                 'txt'=>$txt,
                                 'src'=>'sdk-php-2.0')); // management internal parameter
  $context = stream_context_create(array('http'=>array(
        'method'=>'POST',
        'header'=>
          'Content-type: application/x-www-form-urlencoded'."\r\n".
          'Content-Length: '.strlen($data)."\r\n",
        'content'=>$data)));
  
  $fd = fopen($api, 'r', false, $context);
  $response = stream_get_contents($fd);
  fclose($fd);
  return $response;
} // sendPost

?>
