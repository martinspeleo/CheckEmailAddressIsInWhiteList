<?php

/**
 * Join a string with a natural language conjunction at the end. 
 * https://gist.github.com/angry-dan/e01b8712d6538510dd9c
 */
function natural_language_join(array $list, $conjunction = 'and') {
  $last = array_pop($list);
  if ($list) {
    return implode(', ', $list) . ' ' . $conjunction . ' ' . $last;
  }
  return $last;
}

class CheckEmailAddressIsInWhiteListHooks {

	/* Check if email domain is in white list
	*/
	public static function abortNewAccountDomain ( $user, &$abortError ) {
		global $wgCheckEmailAddressIsInWhiteListDomainSources;

		$fulladdress = $user->getEmail();
		$domain = stristr( $fulladdress, '@' ); /*remove the part before the @*/
		$domain = substr($domain, 1); /*remove the leading @*/
                $domain = mb_strtolower( $domain ); /* change to lower case */
                $domain = rtrim($domain); /* remove trailing white space */
                $domainlevels = explode(".", $domain); /* split domain into its levels */
                $domainlevels = array_reverse($domainlevels); /* reverse order to start with top level domian */

		if( !is_array( $wgCheckEmailAddressIsInWhiteListDomainSources ) || count( $wgCheckEmailAddressIsInWhiteListDomainSources ) <= 0 ) {
			$abortError = wfMessage( 'checkemailaddressisinwhitelist-configerror' )->text();
                        return false;
		}

		if( $wgCheckEmailAddressIsInWhiteListDomainSources[ 'type' ] == CEASRC_FILE ) {
			$srcfile = $wgCheckEmailAddressIsInWhiteListDomainSources[ 'src' ];

			if( file_exists( $srcfile ) ) {
				$domlines = file( $srcfile );
			} else {
				$abortError = wfMessage( 'checkemailaddressisinwhitelist-fileerror' )->text();
                                $abortError =  $wgCheckEmailAddressIsInWhiteListDomainSources[ 'src' ];
				return false;
			}
			foreach( $domlines as $matchdomain ) {
				$matchdomain = mb_strtolower( $matchdomain );
                                $matchdomain = rtrim( $matchdomain );
                                $matchdomainlevels = explode(".", $matchdomain); 
                                $matchdomainlevels = array_reverse($matchdomainlevels);
                                if( count($matchdomainlevels) <= count($domainlevels)) {
                                   $allmatched = true;
                                   foreach (range(0, count($matchdomainlevels) - 1) as $i) {
                                       if ($matchdomainlevels[$i] !== $domainlevels[$i] ) {
                                           $allmatched = false;
                                       }
                                   }
                                }
				if( $allmatched ) {
					unset( $domline );
					return true;
				}
			}
                        $list = natural_language_join($domlines, 'or');
			$abortError = wfMessage( 'checkemailaddressisinwhitelist-domainerror', $list )->text();
			return false;
		}
		return true;
	}
}

