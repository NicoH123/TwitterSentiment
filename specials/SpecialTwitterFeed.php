<?php
/**
 * HelloWorld SpecialPage for BoilerPlate extension
 *
 * @file
 * @ingroup Extensions
 */

class SpecialTwitterFeed extends SpecialPage {
	public function __construct() {
		parent::__construct( 'TwitterFeed' );
	}

	/**
	 * Show the page to the user
	 *
	 * @param string $sub The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].
	 */
	public function execute( $sub ) {
		
		// Path to 140dev Twitter display plugin
		$tpPath = __DIR__ . '/../resources/140dev/plugins/twitter_display/';
		
		 // Get constants for tweet display
		require_once $tpPath . 'twitter_display_config.php';

		$tweet_page = file_get_contents($tpPath . 'tweet_list_template.txt');
		// Here, tweets are shown and Wiki pages are created.
		$tweet_page = str_replace( '[tweets]', 
		require_once $tpPath . 'get_tweet_list.php', 
		$tweet_page); 
		
		
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
		// Nico added 20160113
		$out->addHTML('<link rel="stylesheet" href="' . AJAX_URL . 'default.css" type="text/css" />
						<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js">
						</script>
						<script type="text/javascript" src="' . AJAX_URL . 'site.js">
						</script>');
		$out->addHTML($tweet_page); // Dani
	}
	
	protected function getGroupName() {
		return 'TweetDisplay';
	}
}
?>