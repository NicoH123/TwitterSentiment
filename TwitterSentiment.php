<?php
/**
 * BoilerPlate extension - the thing that needs you.
 *
 * For more info see http://mediawiki.org/wiki/Extension:BoilerPlate
 *
 * @file
 * @ingroup Extensions
 * @author Your Name, 2015
 */

 # Alert the user that this is not a valid access point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
	echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/[MyExtension]/[MyExtension].php" );
EOT;
	exit( 1 );
}

// Not working yet...
#require_once __DIR__ . '/includes/CategoriesAndProperties.php';

 
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'TwitterFeed',
	'author' => array(
		'Your Name',
	),
	'version'  => '0.0.0',
	'url' => 'https://www.mediawiki.org/wiki/Extension:BoilerPlate',
	'descriptionmsg' => 'Hello, Tweet!',
	'license-name' => 'MIT',
);

/* Setup */

// Register files
$wgAutoloadClasses['TwitterSentimentHooks'] = __DIR__ . '/TwitterSentiment.hooks.php';
#$wgAutoloadClasses['CategoriesAndProperties'] = __DIR__ . '/includes/CategoriesAndProperties.php';
$wgAutoloadClasses['SpecialTwitterFeed'] = __DIR__ . '/specials/SpecialTwitterFeed.php';
$wgMessagesDirs['TwitterSentiment'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['BoilerPlateAlias'] = __DIR__ . '/BoilerPlate.i18n.alias.php';

// Register hooks
#$wgHooks['ParserFirstCallInit'][] = 'TwitterSentimentHooks::onParserFirstCallInit';

// Register special pages
$wgSpecialPages['TwitterFeed'] = 'SpecialTwitterFeed';

// Register modules
$wgResourceModules['ext.boilerPlate.foo'] = array(
	'scripts' => 
		'specials/site.js',
		'http://code.jquery.com/jquery-latest.min.js',
	
	'styles' => array(
		'specials/default.css',
	),
	'messages' => array(
	),
	'dependencies' => array(
	),

	'localBasePath' => __DIR__,
	'remoteExtPath' => 'examples/BoilerPlate',
);

/* Configuration */

// Enable Foo
#$wgBoilerPlateEnableFoo = true;



		
