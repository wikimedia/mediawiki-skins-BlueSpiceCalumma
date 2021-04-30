<?php
namespace BlueSpice\Calumma\Components;

use BlueSpice\Calumma\CookieHandler;
use Message;

class SidebarToggle extends \Skins\Chameleon\Components\Structure {

	protected $activeState = false;

	/**
	 * The resulting HTML
	 * @return string
	 */
	public function getHtml() {
		if ( $this->skipRendering() ) {
			return '';
		}

		$dataToggle = $this->getDomElement()->getAttribute( 'data-toggle' );
		$ariaControls = $this->getDomElement()->getAttribute( 'aria-controls' );
		$ariaExpanded = $this->getDomElement()->getAttribute( 'aria-expanded' );
		$class = $this->getDomElement()->getAttribute( 'class' );
		$request = $this->getSkin()->getOutput()->getRequest();
		$cookieHandler = new CookieHandler( $request );
		$sidebarCollapsed = $cookieHandler->getCookie( "Calumma_$dataToggle" );

		if ( $sidebarCollapsed === 'false' ) {
			$ariaExpanded = 'true';
		} else {
			$ariaExpanded = 'false';
		}

		$html = \Html::openElement( 'a', [
			'class' => ' sidebar-toggle ' . $class,
			'data-toggle' => $dataToggle,
			'role' => 'button',
			'title' => $this->makeButtonTitle( $ariaControls, $ariaExpanded ),
			'aria-label' => $this->makeButtonTitle( $ariaControls, $ariaExpanded ),
			'aria-contols' => $ariaControls,
			'aria-expanded' => $ariaExpanded,
			'tabindex' => '0'
		] );

		$html .= \Html::openElement( 'i', [ 'class' => $dataToggle ] );
		$html .= \Html::closeElement( 'i' );

		$html .= \Html::closeElement( 'a' );

		return $html;
	}

	/**
	 * Is this element active
	 * @return bool
	 */
	public function isActive() {
		return $this->activeState;
	}

	/**
	 *
	 * @return bool
	 */
	protected function skipRendering() {
		$hideIfNoRead = $this->getDomElement()->getAttribute( 'hide-if-noread' );
		$hideIfNoRead = strtolower( $hideIfNoRead ) === 'true' ? true : false;
		$userHasReadPermissionsAtAll = !$this->getSkin()->getUser()->isAllowed( 'read' );

		if ( $hideIfNoRead && $userHasReadPermissionsAtAll ) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $controls
	 * @param string $expanded
	 * @return string
	 */
	private function makeButtonTitle( $controls, $expanded ) {
		$sidebar = false;

		if ( $controls === 'navigation-main' ) {
			$sidebar = 'left';
		} elseif ( $controls === 'sitetools-main' ) {
			$sidebar = 'right';
		}
		if ( !$sidebar ) {
			return '';
		}

		if ( $expanded === 'true' ) {
			return Message::newFromKey( "bs-calumma-navigation-toggle-tooltip-$sidebar-close" )->text();
		}

		return Message::newFromKey( "bs-calumma-navigation-toggle-tooltip-$sidebar-open" )->text();
	}

}
