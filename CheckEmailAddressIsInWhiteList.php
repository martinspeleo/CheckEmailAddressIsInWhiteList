<?php
if ( !defined( 'MEDIAWIKI' ) ) {
	exit(1);
}

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'CheckEmailAddressIsInWhiteList',
	'author'         => 'Martin Green',
	'url'            => '',
	'descriptionmsg' => 'checkemailaddressisinwhitelist-desc',
	'version'        => '1.0',
);

$wgExtensionMessagesFiles[ 'CheckEmailAddressIsInWhiteList' ] = dirname( __FILE__ ) . '/CheckEmailAddressIsInWhiteList.i18n.php';
$wgAutoloadClasses[ 'CheckEmailAddressIsInWhiteListHooks' ] = dirname( __FILE__ ) . '/CheckEmailAddressIsInWhiteList.hooks.php';

/** for future integration: CheckEmailAddress source types
 *  @{
 */
define( 'CEASRC_MSG',       0 );	///< For internal usage
define( 'CEASRC_LOCALPAGE', 1 );	///< Local wiki page
define( 'CEASRC_URL',	    2 );	///< Load blacklist from URL
define( 'CEASRC_FILE',      3 );	///< Load from file
/** @}

/** Array of CheckEmailAddress sources */
$wgCheckEmailAddressIsInWhiteListDomainSources = array();

/** Hooks */
$wgHooks['AbortNewAccount'][] = 'CheckEmailAddressIsInWhiteListHooks::abortNewAccountDomain';
