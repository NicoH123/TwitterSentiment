<?php
/**
 * HelloWorld SpecialPage for BoilerPlate extension
 *
 * @file
 * @ingroup Extensions
 */

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
		
		 // Get constants for tweet display
		require_once("twitter_display_config.php");

		$tweet_page = file_get_contents('C:\wamp\www\TwitterWiki\extensions\TwitterSentiment\specials\tweet_list_template.txt');
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
		$out->addModules( 'ext.boilerPlate.foo' );
		$out->setPageTitle( "Twitter Sentiment Analysis" );
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
		return 'TweetDisplay';
	}
}
?>