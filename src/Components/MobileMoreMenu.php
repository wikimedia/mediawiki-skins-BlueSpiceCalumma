<?php
namespace BlueSpice\Calumma\Components;

use BlueSpice\Calumma\SkinDataFieldDefinition as SDFD;
use BlueSpice\Calumma\TemplateComponent;

class MobileMoreMenu extends TemplateComponent {

	/**
	 *
	 * @return string
	 */
	protected function getTemplatePathName() {
		return 'Calumma.Components.MobileMoreMenu';
	}

	/**
	 *
	 * @return array
	 */
	protected function getTemplateArgs() {
		$args = [];

		if ( $this->getSkinTemplate()->getSkin()->getTitle()->isSpecialPage() ) {
			return [];
		}
		if ( $this->getSkinTemplate()->getSkin()->getUser()->isAnon() ) {
			return [];
		}
		$args['show'] = parent::getTemplateArgs();
		$args['show']['title'] = "";
		$args['show']['links'] = $this->getSkinTemplate()->get( SDFD::MOBILE_MORE_MENU );

		if ( !$this->getSkinTemplate()->getSkin()->getTitle()->userCan( 'edit' ) ) {
			$args['show']['isDisabled'] = true;
		}

		return $args;
	}
}
