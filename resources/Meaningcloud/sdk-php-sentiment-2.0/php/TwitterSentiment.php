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

class MeaningcloudSentiment {
	
	public function Sentiment ($tweet_text) {
		
		$api = 'http://api.meaningcloud.com/sentiment-2.0';
		$key = '167884a44a359889e003b50c2e90364d';
		$txt = $tweet_text;
		$model = 'general_en';  // general_en / general_es / general_fr 
		
		// Mapping to unify schema in MediaWiki
		$mapping = array('P+' => 'strong positive',
						 'P' => 'positive',
						 'NEU' => 'neutral',
						 'N' => 'negative',
						 'N+' => 'strong negative',
						 'NONE' => 'none');

		// We make the request and parse the response to an array
		$response = $this->sendPost($api, $key, $model, $txt);
		$json = json_decode($response, true);


		// Returns the specific fields in the response (sentiment)
		if(isset($json['score_tag'])) {
		  return $mapping[$json['score_tag']];
		} else {
			return "none";
		}
		
	}
	

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
	
}
 


?>
