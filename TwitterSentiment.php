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
 
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'HelloTweet',
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
$wgAutoloadClasses['SpecialTweet4'] = __DIR__ . '/specials/SpecialTweet4.php';
$wgAutoloadClasses['BoilerPlateHooks'] = __DIR__ . '/BoilerPlate.hooks.php';
$wgAutoloadClasses['SpecialHelloTweet'] = __DIR__ . '/specials/SpecialHelloTweet.php';
$wgAutoloadClasses['SpecialTweet1'] = __DIR__ . '/specials/SpecialTweet1.php';
$wgMessagesDirs['TwitterSentiment'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['BoilerPlateAlias'] = __DIR__ . '/BoilerPlate.i18n.alias.php';

// Register hooks
#$wgHooks['NameOfHook'][] = 'BoilerPlateHooks::onNameOfHook';

// Register special pages
$wgSpecialPages['Tweet4'] = 'SpecialTweet4';
$wgSpecialPages['HelloTweet'] = 'SpecialHelloTweet';
$wgSpecialPages['Tweet1'] = 'SpecialTweet1';

// Register modules
$wgResourceModules['ext.boilerPlate.foo'] = array(
	'scripts' => 
		'specials/site.js',
	
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
