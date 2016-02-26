<?php

class DatumboxTwitterSentiment {
	
	public function TwitterSentiment ($tweet_text) {
	
		require_once('DatumboxAPI.php');

		$api_key='d4a4b20a7ed191960a123464873ebfb4';

		$DatumboxAPI = new DatumboxAPI($api_key);
		
		// PHP makes problems with "@" in HTTP POST --> workaround.
		$tweet_escaped = addcslashes ( $tweet_text , '@' );
		
		$DocumentClassification['TwitterSentimentAnalysis']=$DatumboxAPI->TwitterSentimentAnalysis($tweet_escaped);
		
		unset($DatumboxAPI);
		
		
		//Return the result
		if(isset($DocumentClassification['TwitterSentimentAnalysis'])) {
			return $DocumentClassification['TwitterSentimentAnalysis'];
		} else {
			return "none";
		}


	}

	
}


