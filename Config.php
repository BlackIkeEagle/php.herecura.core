<?php
/**
 * Default minimal Config Class.
 * override this in the folder site with the real config
 *
 * @author Ike Devolder <ike DOT devolder AT gmail DOT com>
 */
class Config {
	const DEFAULTPAGE = 'about';

	/**
	 * site info configuration
	 *
	 * @static
	 * @access public
	 * @return void
	 */
	public static function siteInfo() {
		$startYear = 2011;
		$company = 'Herecura';
		return (object) array(
			'site' => 'php.herecura.core',
			'version' => '0.1',
			'versionExtension' => 'alpha',
			'copyright' => '&copy; '.$startYear.(date('Y') != $startYear ? ' - '.date('Y') : '').' '.$company,
			'company' => $company,
			'creator' => 'Ike Devolder <ike DOT devolder AT gmail DOT com>',
			'contributors' => array(
			)
		);
	}
}
