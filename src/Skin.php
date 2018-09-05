<?php

namespace BlueSpice\Calumma;

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
				'skin.bluespicecalumma.flyout.recentchanges'
			]
		);
	}

	/**
	 * @param OutputPage $out
	 */
	public function setupSkinUserCss( \OutputPage $out ) {
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( [
			'skin.bluespicecalumma.styles',
			'skin.bluespicecalumma.dynamicoffcanvas.styles',
		] );
	}

	/**
	 * This will be called by OutputPage::headElement when it is creating the
	 * "<body>" tag, - adds output property bodyClassName to the existing classes
	 * @param OutputPage $out
	 * @param array &$bodyAttrs
	 */
	public function addToBodyAttributes( $out, &$bodyAttrs ) {
		$classes = $out->getProperty( 'bodyClassName' );
		$request = $this->getRequest();

		$cookie_navigation_main_sticky = $request->getCookie( 'Calumma_navigation-main-sticky' );
		$cookie_sitetools_main_sticky = $request->getCookie( 'Calumma_sitetools-main-sticky' );

		$cookie_navigation_main_collapsed = $request->getCookie( 'Calumma_navigation-main-collapse' );
		$cookie_sitetools_main_collapsed = $request->getCookie( 'Calumma_sitetools-main-collapse' );

		$bodyAttrs[ 'class' ] .= $this->checkToggleState(
			$cookie_navigation_main_sticky,
			$cookie_navigation_main_collapsed,
			'navigation'
		);
		$bodyAttrs[ 'class' ] .= $this->checkToggleState(
			$cookie_sitetools_main_sticky,
			$cookie_sitetools_main_collapsed,
			'sitetools'
		);

		$bodyAttrs[ 'class' ] .= ' navigation-main-fixed sitetools-main-fixed ' . $classes;

		if ( !$this->getSkin()->getUser()->isLoggedIn() ) {
			$bodyAttrs[ 'class' ] .= ' calumma-not-logged-in ';
		}
	}

	/**
	 *
	 * @return array
	 */
	public function getDefaultModules() {
		$modules = parent::getDefaultModules();
		unset( $modules[ 'search' ] );
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
	 * @param string $cookie_sticky
	 * @param string $cookie_state
	 * @param string $nav
	 * @return string
	 */
	protected function checkToggleState( $cookie_sticky, $cookie_state, $nav ) {
		$option = 'bs-calumma-settings-' . $nav . '-main-collapse';
		$userSetting = $this->getSkin()->getUser()->getOption( $option );

		// fallback
		$userSettingClass = '';
		if ( ( !isset( $userSetting ) ) || ( $userSetting === true ) || ( $userSetting === '1' ) ) {
			$userSettingClass = $nav . '-main-collapse ';
		}

		$cookieStateClass = '';
		if ( ( !isset( $cookie_state ) ) || ( $cookie_state === 'true' ) ) {
			$cookieStateClass = $nav . '-main-collapse ';
		}

		$cookieStickyClass = '';
		if ( $cookie_sticky === 'true' ) {
			$cookieStickyClass = $nav . '-main-sticky ';
		}

		$class = '';

		if ( $cookieStickyClass === '' ) {
			$class = $userSettingClass;
		} else {
			$class = $cookieStickyClass . ' ';
			$class .= $cookieStateClass . ' ';
		}

		return ' ' . $class . ' ';
	}

}
