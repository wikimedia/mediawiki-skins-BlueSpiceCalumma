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
		$cookieHandler = new CookieHandler( $request );

		$cookieDesktopView = null;
		$cookieDesktopView = $cookieHandler->getCookie( 'Calumma_desktop-view' );

		$cookieFullScreenMode = null;
		$cookieFullScreenMode = $cookieHandler->getCookie( 'Calumma_bs-full-screen-mode' );

		$desktopView = false;
		$fullScreenMode = false;

		if ( ( $cookieDesktopView === null ) || ( $cookieDesktopView === 'true' ) ) {
			$desktopView = true;
		}

		if ( ( $cookieFullScreenMode !== null ) && ( $cookieFullScreenMode === 'true' ) ) {
			$fullScreenMode = true;
		}

		$bodyAttrs[ 'class' ] .= $this->checkCustomMenuState( 'header' );
		$bodyAttrs['class'] .= ' navigation-main-fixed sitetools-main-fixed ' . $classes;

		if ( ( $desktopView === true ) && ( $fullScreenMode === false ) ) {
			$cookieNavigationMainSticky =
				$cookieHandler->getCookie( 'Calumma_navigation-main-sticky' );
			$cookieSitetoolsMainSticky =
				$cookieHandler->getCookie( 'Calumma_sitetools-main-sticky' );

			$cookieNavigationMainCollapsed =
				$cookieHandler->getCookie( 'Calumma_navigation-main-collapse' );
			$cookieSitetoolsMainCollapsed =
				$cookieHandler->getCookie( 'Calumma_sitetools-main-collapse' );

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
		} elseif ( ( $desktopView === true ) && ( $fullScreenMode === true ) ) {
			$bodyAttrs[ 'class' ] .=
				' navigation-main-collapse sitetools-main-collapse bs-full-screen-mode ';
		} else {
			$bodyAttrs[ 'class' ] .= ' navigation-main-collapse sitetools-main-collapse ';
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
		$userSetting = $this->getSkin()->getUser()->getOption( $option, null );
		$userIsLoggedIn = $this->getSkin()->getUser()->isLoggedIn();

		$userSettingClass = '';
		if ( $userSetting === null || (bool)$userSetting === true ) {
			$userSettingClass = $nav . '-main-collapse ';
		}

		$cookieStateClass = '';
		if ( isset( $cookie_state ) && ( $cookie_state === 'true' ) ) {
			$cookieStateClass = $nav . '-main-collapse ';
		}

		$class = '';
		if ( !$userIsLoggedIn ) {
			$class = $nav . '-main-collapse ';
		} elseif ( $userSettingClass !== '' ) {
			$class = $userSettingClass;
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
		$cookieHandler = new CookieHandler( $this->getRequest() );
		$cookieValue = $cookieHandler->getCookie( $cookieName, 'false' );
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

}
