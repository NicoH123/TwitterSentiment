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
		$out = $this->getOutput();

		$out->setPageTitle( "Twitter Sentiment Analysis" );

		$out->addWikiMsg( 'boilerplate-helloworld-intro' );
		$out->addWikiText("This is some ''lovely'' [[wikitext]] that will '''get''' parsed nicely.");
		$out->addHTML($this->sandboxParse("Here's some '''formatted''' text."));

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
