<?php
/**
 * HelloWorld SpecialPage for BoilerPlate extension
 *
 * @file
 * @ingroup Extensions
 */
include("TestIterator.php");
class SpecialHelloTweet extends SpecialPage {
	public function __construct() {
		parent::__construct( 'HelloTweet' );
	}

	/**
	 * Show the page to the user
	 *
	 * @param string $sub The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].
	 */
	public function execute( $sub ) {
		
		$tweet_page = file_get_contents('C:\wamp\www\TwitterWiki\extensions\TwitterSentiment\specials\tweet_list_template.txt');
		$tweet_page = str_replace( '[tweets]', 
		require_once('show_tweets.php'), $tweet_page); // Veränderung
		
		//Problem: Eigentlich sollte ich einen Token zum Edit erhalten;
		//ich erhalte aber nur den "anonymen User" Token.
		// am besten fixen - im Notfall weglassen, geht auch ohne.
		
		//step 1 - Initialisierung von Curl
		$cSession1 = curl_init(); 
		//step2 - Optionen von Curl setzen; lässt sich auch mit einem Array lesen
		curl_setopt($cSession1,CURLOPT_URL,"http://localhost/TwitterWiki/api.php?action=query&meta=tokens");
		curl_setopt($cSession1,CURLOPT_RETURNTRANSFER,0);
		curl_setopt($cSession1,CURLOPT_HEADER, false); 
		//step3
		$result=curl_exec($cSession1);
		//step4
		curl_close($cSession1);
		print $result;
		
		
		/*
		In dieser Curl Initialisierung senden wir unsere Post-Anfrage mit allen nötigen Informationen.
		Eigentlich macht es hier wenig Sinn, wollte es nur mal drin lassen, damit man die andere OptArray sieht.
		Darf danach also gelöscht werden.
		*/
		
		$cSession2 = curl_init();	
		  //step2 - hier als Kontrast mit Array gelöst
		curl_setopt_array($cSession2, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://localhost/TwitterWiki/api.php?',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
        'action' => 'edit',
        'title' => 'WinzTest2',
		'section' => 'new',
		'text' => 'Hello%20World',
		'token' => "+\\",
			)
		));
		//step3
		$curl_result=curl_exec($cSession2);

		if(!curl_exec($cSession2)){
			die('Error: "' . curl_error($cSession2) . '" - Code: ' . curl_errno($cSession2));
			echo curl_errno();
	}
	

		//step4
		curl_close($cSession2);
		
		// Optional
		$tweet_page = str_replace( '[new_count_refresh]', 
		NEW_COUNT_REFRESH, $tweet_page); 
		$tweet_page = str_replace( '[ajax_url]', 
		AJAX_URL, $tweet_page); 
		$tweet_page = str_replace( '[more_button]', 
		MORE_BUTTON, $tweet_page); 
		//Optional Ende
		
		$out = $this->getOutput();
		$out->setPageTitle( "Twitter Sentiment Analysis" );
		$out->addWikiMsg( 'boilerplate-helloworld-intro' );
		$out->addWikiText("This is some ''lovely'' [[wikitext]] that will '''get''' parsed nicely.");
		$out->addHTML($this->sandboxParse("Here's some '''formatted''' text."));
		$out->addHTML($tweet_page); // Veränderung
	}

	# Workaround to include SpecialPage in other pages.
	function sandboxParse($wikiText) {
		global $wgTitle, $wgUser;
		$myParser = new Parser();
		$myParserOptions = ParserOptions::newFromUser($wgUser);
		$result = $myParser->parse($wikiText, $wgTitle, $myParserOptions);
		return $result->getText();
	}
	
	protected function getGroupName() {
		return 'nicotest';
	}
}
