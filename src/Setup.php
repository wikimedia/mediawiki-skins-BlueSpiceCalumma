<?php

namespace BlueSpice\Calumma;

class Setup {
	/**
	 *
	 */
	public static function onCallback() {
		$GLOBALS[ 'egChameleonLayoutFile' ] = dirname( __DIR__ ) . '/layouts/default.xml';

		$GLOBALS[ 'wgUseMediaWikiUIEverywhere' ] = true;

		$GLOBALS[ 'wgVisualEditorSupportedSkins' ][] = 'bluespicecalumma';
		$GLOBALS[ 'wgVisualEditorSkinToolbarScrollOffset' ][ 'bluespicecalumma' ] = 64;
	}
}
