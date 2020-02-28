<?php

namespace BlueSpice\Calumma;

use BlueSpice\Services;

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

		$cookieDesktopView = null;
		$cookieDesktopView = $request->getCookie( 'Calumma_desktop-view' );

		$cookieFullSreenMode = null;
		$cookieFullSreenMode = $request->getCookie( 'Calumma_bs-full-screen-mode' );

		$desktopView = false;
		$fullSreenMode = false;

		if ( ( $cookieDesktopView === null ) || ( $cookieDesktopView === 'true' ) ) {
			$desktopView = true;
		}

		if ( ( $cookieFullSreenMode !== null ) && ( $cookieFullSreenMode === 'true' ) ) {
			$fullSreenMode = true;
		}

		$bodyAttrs[ 'class' ] .= $this->checkCustomMenuState( 'header' );

		if ( ( $desktopView === true ) && ( $fullSreenMode === false ) ) {
			$cookieNavigationMainSticky =
				$request->getCookie( 'Calumma_navigation-main-sticky' );
			$cookieSitetoolsMainSticky =
				$request->getCookie( 'Calumma_sitetools-main-sticky' );

			$cookieNavigationMainCollapsed =
				$request->getCookie( 'Calumma_navigation-main-collapse' );
			$cookieSitetoolsMainCollapsed =
				$request->getCookie( 'Calumma_sitetools-main-collapse' );

			$bodyAttrs[ 'class' ] .= $this->checkToggleState(
				$cookieNavigationMainSticky,
				$cookieNavigationMainCollapsed,
				'navigation'
			);
			$bodyAttrs[ 'class' ] .= $this->checkToggleState(
				$cookieSitetoolsMainSticky,
				$cookieSitetoolsMainCollapsed,
				'sitetools'
			);
		} elseif ( ( $desktopView === true ) && ( $fullSreenMode === true ) ) {
			$bodyAttrs[ 'class' ] .=
				' navigation-main-collapse sitetools-main-collapse bs-full-screen-mode ';
		} else {
			$bodyAttrs[ 'class' ] .= ' navigation-main-collapse sitetools-main-collapse ';
		}
		$bodyAttrs[ 'class' ] .= ' navigation-main-fixed sitetools-main-fixed ' . $classes;
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
		$userIsLoggedIn = $this->getSkin()->getUser()->isLoggedIn();

		$userSettingClass = '';
		if ( ( !isset( $userSetting ) ) || ( $userSetting === true ) || ( $userSetting === '1' ) ) {
			$userSettingClass = $nav . '-main-collapse ';
		}

		$cookieStateClass = '';
		if ( isset( $cookie_state ) && ( $cookie_state === 'true' ) ) {
			$cookieStateClass = $nav . '-main-collapse ';
		}

		$class = '';
		if ( !$userIsLoggedIn ) {
			$class = $userSettingClass;
		} elseif ( $userSettingClass !== '' ) {
			$class = $nav . '-main-collapse ';
		} else {
			$class = $cookieStateClass;
		}

		return ' ' . $class . ' ';
	}

	/**
	 * ATTENTION: There is related code in BlueSpice\Calumma\Components\CustomMenu::skipRendering
	 * @param string $menu
	 * @return string
	 */
	protected function checkCustomMenuState( $menu ) {
		$className = 'bs-custom-menu-' . $menu . '-container-collapse';
		$cookieName = "Calumma_$className";
		$cookieValue = $this->getRequest()->getCookie( $cookieName, null, 'false' );
		$userHasReadPermissionsAtAll = $this->getUser()->isAllowed( 'read' );

		if ( $cookieValue !== 'false' || !$userHasReadPermissionsAtAll ) {
			return " $className ";
		}
	}

	/**
	 *
	 * @return \Config
	 */
	public function getConfig() {
		return Services::getInstance()->getConfigFactory()->makeConfig( 'bsg' );
	}

}
