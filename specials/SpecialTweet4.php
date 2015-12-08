<?php
/**
 * HelloWorld SpecialPage for BoilerPlate extension
 *
 * @file
 * @ingroup Extensions
 */
 
class SpecialTweet4 extends SpecialPage {
	
	/**
	* Constructor
	*/
	public function __construct() {
		parent::__construct( 'Tweet4' );

	}
	
	/**
	 * Show the page to the user
	 *
	 * @param string $sub The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].
	 */
	public function execute( $sub ) {
		$out = $this->getOutput();

		$out->setPageTitle( 'Tweet4' );

		$out->addWikiMsg( 'boilerplate-helloworld-intro' );
		$out->addWikiText('This is a [[Category:Tweet]] Tweet. It was written by [[Was written by::User1]] User1.');
		
		$out->addWikiText('{|
							! align=\'left\'| Gegenstand
							! Menge
							! Kosten
							|-
							|Orange
							|10
							|7.00
							|-
							|Brot
							|4
							|3.00
							|-
							|Butter
							|1
							|5.00
							|-
							!Total
							|
							|15.00
							|}');
									
	}
}