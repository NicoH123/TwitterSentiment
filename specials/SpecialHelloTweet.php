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
		#$out = $this->getOutput();

		#$out->setPageTitle( "Twitter Sentiment Analysis" );
		
		$tweet_page = file_get_contents('C:\wamp\www\TwitterWiki\extensions\TwitterSentiment\specials\tweet_list_template.txt');
		#$test = array("Fruechte", "Gemuese", "Ballaststoffe");
		$tweet_page = str_replace( '[tweets]', 
		require_once('show_tweets.php'), $tweet_page); // Veränderung
		
		
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
