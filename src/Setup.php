<?php

namespace BlueSpice\Calumma;

use MWStake\MediaWiki\Component\CommonUserInterface\LessVars;

class Setup {
	/**
	 *
	 */
	public static function onCallback() {
		$GLOBALS[ 'egChameleonLayoutFile' ] = dirname( __DIR__ ) . '/layouts/default.xml';

		$GLOBALS[ 'wgUseMediaWikiUIEverywhere' ] = true;

		$GLOBALS[ 'wgVisualEditorSupportedSkins' ][] = 'bluespicecalumma';
		$GLOBALS[ 'wgVisualEditorSkinToolbarScrollOffset' ][ 'bluespicecalumma' ] = 64;

		// force overwrite of the custom header registry even when the skin is
		// loaded before the BlueSpiceCustomMenu extension. ERM:16808
		$GLOBALS[ 'bsgExtensionAttributeRegistryOverrides' ]["BlueSpiceCustomMenuRegistry"] = [
			"merge" => [
				"header" => "\\BlueSpice\\Calumma\\CustomMenu\\Header::getInstance"
			]
		];

		self::setLessVars();
	}

	/**
	 *
	 */
	private static function setLessVars() {
		$lessVars = LessVars::getInstance();

		$lessVars->setVar( 'content-width', '61.25rem' );
		$lessVars->setVar( 'body-bg', '#ffffff' );
		$lessVars->setVar( 'content-bg', '#ffffff' );
		$lessVars->setVar( 'content-fg', '#333333' );
		$lessVars->setVar( 'content-h1-fg', '#333333' );
		$lessVars->setVar( 'content-h1-border', 'none' );
		$lessVars->setVar( 'content-h2-fg', '#333333' );
		$lessVars->setVar( 'content-h2-border', '1px solid currentColor' );
		$lessVars->setVar( 'content-h3-fg', '#333333' );
		$lessVars->setVar( 'content-h3-border', 'none' );
		$lessVars->setVar( 'content-h4-fg', '#333333' );
		$lessVars->setVar( 'content-h4-border', 'none' );
		$lessVars->setVar( 'content-h5-fg', '#333333' );
		$lessVars->setVar( 'content-h5-border', 'none' );
		$lessVars->setVar( 'content-h6-fg', '#333333' );
		$lessVars->setVar( 'content-h6-border', 'none' );
	}
}
