<?php

/**
 * This class offers the functionality to create a new Tweet page by passing
 * the TweetID (tid) as a parameter.
 * 
 * 
 */

class TestIterator {
	# Create a new Tweet page by the name of 'SpecialTweet${tid}.php'
	# in the directory /specials of the extension.
	public function create_tweet_page ( $tid ) {
		
		# TODO: What to do if page already exists? In the meantime, just exit.
		if(file_exists('SpecialTweet' . $tid . '.php')) {
			die('Page SpecialTweet' . $tid . '.php already exists!');
		}
		
		# This is the template for a Tweet page. It is work in progress.
		# The String 'XXX' is replaced by the $tid (ugly, fix!).
		$code = '<?php
/**
 * HelloWorld SpecialPage for BoilerPlate extension
 *
 * @file
 * @ingroup Extensions
 */
 
	class SpecialTweetXXX extends SpecialPage {
	
	/**
	* Constructor
	*/
	public function __construct() {
		parent::__construct( \'TweetXXX\' );

	}
	
	/**
	 * Show the page to the user
	 *
	 * @param string $sub The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].
	 */
	public function execute( $sub ) {
		$out = $this->getOutput();

		$out->setPageTitle( \'TweetXXX\' );

		$out->addWikiMsg( \'boilerplate-helloworld-intro\' );
		$out->addWikiText(\'This is a [[Category::Tweet]] Tweet. It was written by [[Was written by::User1]] User1.\');
		
		$out->addWikiText(\'{|
							! align=\\\'left\\\'| Gegenstand
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
							|}\');
									
	}
}';
		
		# Replace 'XXX' by $tid.
		$code = str_replace('XXX', $tid, $code);
		
		# Parse extension name dynamically.
		$cwdFolders = explode("\\", getcwd());
		$cwdFoldersCount = count($cwdFolders);
		$extensionName = $cwdFolders[$cwdFoldersCount - 2];
		
		
		# EDIT OUR EXTENSION ENTRY POINT:
		
		# Load entry point content.
		$contents = file_get_contents('../' . $extensionName . '.php');
		
		# Register new file SpecialTweet.
		$contents = str_replace('// Register files', 
								'// Register files' . "\n" . 
									'$wgAutoloadClasses[\'SpecialTweet' . 
									$tid . '\'] = __DIR__ . \'/specials/SpecialTweet' . 
									$tid . '.php\';', 
								$contents);		
		
		# Register new special page SpecialTweet.
		$contents = str_replace('// Register special pages', 
								'// Register special pages' . "\n" . 
									'$wgSpecialPages[\'Tweet' . $tid . '\'] = \'SpecialTweet' . $tid . '\';', 
								$contents);
		
		# Write back to entry point.
		$entryPoint = fopen('../' . $extensionName . '.php', 'w');
		fwrite($entryPoint, $contents);
		fclose($entryPoint);
		
		
		
		# Finally create special page.
		$newfile = fopen('SpecialTweet' . $tid . '.php', 'w') or die('Unable to open file!');
		fwrite($newfile, $code);
		fclose($newfile);
	}
}

# This is just to test the above functionality. TODO remove from here.
$iterator = new TestIterator();
#$iterator->create_tweet_page(30);

?>