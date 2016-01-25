<?php

class Sentiment140TwitterSentiment {
	
	public function TwitterSentiment ($tweet_text) {
		
		$apiEndpoint = "http://www.sentiment140.com/api/classify?";
		$appid = "nico.haubner@gmail.com";
		
		// Mapping to unify schema in MediaWiki
		$mapping = array('4' => 'positive',
						 '2' => 'neutral',
						 '0' => 'negative');
		
		$params = array('text' => $tweet_text,
						'appid' => $appid);
						
		$query_url = $apiEndpoint . http_build_query($params);
		
		$cSession = curl_init();
		
		curl_setopt_array($cSession, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $query_url
			)
		);
		$curl_result = curl_exec($cSession);
		
		curl_close($cSession);
		
		$result = $mapping[json_decode($curl_result, true)['results']['polarity']];
		
		//Return the result
		return $result;

	}
	
	
}


