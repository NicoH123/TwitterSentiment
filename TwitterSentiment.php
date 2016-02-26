<?php
/**
 * TwitterSentiment extension for Semantic MediaWiki
 * Based on: BoilerPlate extension - the thing that needs you.
 *
 * For more info see http://mediawiki.org/wiki/Extension:BoilerPlate
 *
 * @file
 * @ingroup Extensions
 * @author Daniel Wenz & Nicolas Haubner, 2015
 */

 # Alert the user that this is not a valid access point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
	echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/[MyExtension]/[MyExtension].php" );
EOT;
	exit( 1 );
}

 
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'TwitterFeed',
	'author' => array(
		'Daniel Wenz',
		'Nicolas Haubner'
	),
	'version'  => '0.0.1',
	'url' => 'https://github.com/NicoH123/TwitterSentiment',
	'descriptionmsg' => 'Twitter Sentiment Analysis',
	'license-name' => 'GNU',
);

/* Setup */

// Register files
$wgAutoloadClasses['TwitterSentimentHooks'] = __DIR__ . '/TwitterSentiment.hooks.php';
$wgAutoloadClasses['SpecialTwitterFeed'] = __DIR__ . '/specials/SpecialTwitterFeed.php';
$wgMessagesDirs['TwitterSentiment'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['BoilerPlateAlias'] = __DIR__ . '/BoilerPlate.i18n.alias.php';

// Register special pages
$wgSpecialPages['TwitterFeed'] = 'SpecialTwitterFeed';



		
