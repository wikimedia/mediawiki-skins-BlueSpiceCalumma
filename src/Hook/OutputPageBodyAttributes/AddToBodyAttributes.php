<?php

namespace BlueSpice\Calumma\Hook\OutputPageBodyAttributes;

use BlueSpice\Calumma\CookieHandler;
use BlueSpice\Hook\OutputPageBodyAttributes;

class AddToBodyAttributes extends OutputPageBodyAttributes {

	protected function doProcess() {
		$classes = $this->out->getProperty( 'bodyClassName' );
		$cookieHandler = new CookieHandler( $this->skin->getRequest() );

		$cookieDesktopView = $cookieHandler->getCookie( 'Calumma_desktop-view' );
		$cookieFullScreenMode = $cookieHandler->getCookie( 'Calumma_bs-full-screen-mode' );

		$desktopView = false;
		$fullScreenMode = false;

		if ( $cookieDesktopView === null || $cookieDesktopView === 'true' ) {
			$desktopView = true;
		}

		if ( $cookieFullScreenMode !== null && $cookieFullScreenMode === 'true' ) {
			$fullScreenMode = true;
		}

		$this->bodyAttrs[ 'class' ] .= $this->checkCustomMenuState( 'header' );
		$this->bodyAttrs[ 'class' ] .= ' navigation-main-fixed sitetools-main-fixed ' . $classes;

		if ( $desktopView === true && $fullScreenMode === false ) {
			$cookieNavigationMainSticky =
				$cookieHandler->getCookie( 'Calumma_navigation-main-sticky' );
			$cookieSitetoolsMainSticky =
				$cookieHandler->getCookie( 'Calumma_sitetools-main-sticky' );

			$cookieNavigationMainCollapsed =
				$cookieHandler->getCookie( 'Calumma_navigation-main-collapse' );
			$cookieSitetoolsMainCollapsed =
				$cookieHandler->getCookie( 'Calumma_sitetools-main-collapse' );

			$this->bodyAttrs[ 'class' ] .= $this->checkToggleState(
				$cookieNavigationMainSticky,
				$cookieNavigationMainCollapsed,
				'navigation'
			);
			$this->bodyAttrs[ 'class' ] .= $this->checkToggleState(
				$cookieSitetoolsMainSticky,
				$cookieSitetoolsMainCollapsed,
				'sitetools'
			);
		} elseif ( $desktopView === true && $fullScreenMode === true ) {
			$this->bodyAttrs[ 'class' ] .=
				' navigation-main-collapse sitetools-main-collapse bs-full-screen-mode ';
		} else {
			$this->bodyAttrs[ 'class' ] .= ' navigation-main-collapse sitetools-main-collapse ';
		}
	}

	/**
	 * ATTENTION: There is related code in BlueSpice\Calumma\Components\CustomMenu::skipRendering
	 * @param string $menu
	 * @return string
	 */
	protected function checkCustomMenuState( $menu ) {
		$className = 'bs-custom-menu-' . $menu . '-container-collapse';

		$cookieName = "Calumma_$className";
		$cookieHandler = new CookieHandler( $this->skin->getRequest() );
		$cookieValue = $cookieHandler->getCookie( $cookieName, 'null' );

		$userHasReadPermissionsAtAll = $this->getServices()->getPermissionManager()->userHasRight(
			$this->skin->getUser(),
			'read'
		);

		if ( $cookieValue === 'true' || !$userHasReadPermissionsAtAll ) {
			return " $className ";
		}

		if ( $cookieValue === 'false' ) {
			return "";
		}

		$customMenuConfig = $this->getConfig()->get( 'BlueSpiceCalummaCustomMenuHeaderCollapse' );

		if ( $customMenuConfig === true || $customMenuConfig === 1 ) {
			return " $className ";
		}

		return "";
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
		$userSetting = $this->skin->getUser()->getOption( $option, null );
		$userIsLoggedIn = $this->skin->getUser()->isLoggedIn();

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

}
