<?php

namespace BlueSpice\Calumma;

use ExtensionRegistry;
use MediaWiki\MediaWikiServices;

/**
 *
 * @ingroup Skins
 */
class Skin extends \SkinChameleon {
	public $skinname = 'bluespicecalumma';
	public $stylename = 'bluespicecalumma';
	public $template = '\BlueSpice\Calumma\Template';
	public $useHeadElement = true;

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param OutputPage $out
	 */
	public function initPage( \OutputPage $out ) {
		parent::initPage( $out );
		$out->addModules( [
				'skin.bluespicecalumma.scripts',
				'skin.bluespicecalumma.dynamicoffcanvas',
				'skin.bluespicecalumma.panel',
				'skin.bluespicecalumma.flyout.recentchanges',
				'skin.bluespicecalumma.ajaxWatch'
			]
		);
	}

	/**
	 *
	 * @return array
	 */
	public function getDefaultModules() {
		$modules = parent::getDefaultModules();
		if ( !isset( $modules['styles']['skin'] ) ) {
			$modules['styles']['skin'] = [];
		}
		array_unshift( $modules['styles']['skin'], 'ext.bootstrap.styles' );
		if ( isset( $modules[ 'search' ] ) ) {
			unset( $modules[ 'search' ] );
		}
		return $modules;
	}

	/**
	 * Return values for <html> element
	 * @return array Array of associative name-to-value elements for <html> element
	 */
	public function getHtmlElementAttributes() {
		$lang = $this->getLanguage();
		return [
			'lang' => $lang->getHtmlCode(),
			'dir' => $lang->getDir(),
			'class' => 'client-nojs',
		];
	}

	/**
	 *
	 * @return \Config
	 */
	public function getConfig() {
		return MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'bsg' );
	}

	/**
	 * Make sure ParserFunctions within `MediaWiki:Sidebar` are evaluated
	 * @param array &$bar
	 * @param string $message
	 */
	public function addToSidebar( &$bar, $message ) {
		if ( $message === 'sidebar' ) {
			$this->addToSidebarPlain( $bar, wfMessage( $message )->inContentLanguage()->text() );
			return;
		}
		parent::addToSidebar( $bar, $message );
	}

	/**
	 * @inheritDoc
	 */
	protected function prepareQuickTemplate() {
		$tpl = parent::prepareQuickTemplate();
		$allThings = ExtensionRegistry::getInstance()->getAllThings();
		// See: T254300
		// This hook will be available in Chameleon 3.0.1 and should therefore never
		// be executed a second time. We remove this whole method whenever we upgrade
		// to the newest version of Chameleon. Since CHAMELEON_VERSION was removed
		// in favor of the version variable in extension.json in a newer version,
		// we simply need to check if chameleon was registered within extension.json
		if ( isset( $allThings['chameleon'] )
			&& version_compare( $allThings['chameleon'], '3.0.1', '>=' ) ) {
			wfDebugLog( 'bluespice-deprecations', __METHOD__, 'private' );
			return $tpl;
		}
		MediaWikiServices::getInstance()->getHookContainer()->run(
			'ChameleonSkinTemplateOutputPageBeforeExec',
			[
				$this,
				$tpl
			]
		);
		return $tpl;
	}

}
