<?php
namespace BlueSpice\Calumma\Components;

use BlueSpice\Services;

class CustomMenu extends \Skins\Chameleon\Components\Structure {

	/**
	 *
	 * @return string
	 */
	public function getHtml() {
		$menu = $this->getDomElement()->getAttribute( 'data-menu' );

		if ( !$this->getCutomMenu( $menu ) ) {
			$customMenu = '';
			$triggerButton = '';
			$editLink = '';
		} else {
			$customMenu = $this->getCutomMenu( $menu );
			$triggerButton = parent::getHtml();
			$editLink = $this->addEditLink(
					$this->getSkinTemplate(),
					$menu
				);
		}
		$class = $this->getDomElement()->getAttribute( 'class' );
		$class .= " bs-custom-menu-$menu-container navbar navbar-fixed-top";

		$html = \Html::rawElement(
			'nav',
			[ 'class' => $class ],
			$customMenu . $editLink
		);

		$html .= $triggerButton;

		return $html;
	}

	/**
	 *
	 * @param string $menu
	 * @param mixed $default
	 * @return string
	 */
	protected function getCutomMenu( $menu, $default = false ) {
		$customMenus = $this->getSkinTemplate()->get(
			\BlueSpice\SkinData::CUSTOM_MENU
		);
		if ( !isset( $customMenus[$menu] ) ) {
			return $default;
		}

		return $customMenus[$menu];
	}

	/**
	 *
	 * @param SkinTemplate $skintemplate
	 * @param string $menu
	 * @return string
	 */
	protected function addEditLink( $skintemplate, $menu ) {
		$html = '';

		if ( $skintemplate->getSkin()->getUser()->isAllowed( 'editinterface' ) ) {
			$factory = Services::getInstance()->getService( 'BSCustomMenuFactory' );

			if ( $factory->getMenu( $menu )->getEditURL() === null ) {
				return '';
			}

			$html .= \Html::openElement(
				'a',
				[
					'href' => $factory->getMenu( $menu )->getEditURL(),
					'title' => wfMessage( 'bs-edit-custom-menu-link-title' )->plain(),
					'target' => '_blank',
					'class' => 'bs-edit-custom-menu-link',
					'iconClass' => ''
				]
			);

			$html .= \Html::element(
					'span',
					[
						'class' => 'label'
					],
					wfMessage( 'bs-edit-custom-menu-link-text' )->plain()
				);

			$html .= \Html::closeElement( 'a' );
		}

		return $html;
	}

}
