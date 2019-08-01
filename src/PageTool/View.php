<?php

namespace BlueSpice\Calumma\PageTool;

use BlueSpice\PageTool\IconBase;

class View extends IconBase {

	/**
	 *
	 * @return string
	 */
	protected function getIconClass() {
		return 'bs-icon-text';
	}

	/**
	 *
	 * @return \Message
	 */
	protected function getToolTip() {
		return new \Message( 'bs-calumma-pagetool-view-tooltip' );
	}

	/**
	 *
	 * @return string
	 */
	protected function getUrl() {
		$url = $this->getTitle()->getLocalURL( [ 'action' => 'view' ] );
		return $url;
	}

	/**
	 *
	 * @return bool
	 */
	protected function skipProcessing() {
		if ( $this->getTitle()->isSpecialPage() ) {
			return true;
		}

		$action = $this->context->getRequest()->getVal( 'action', 'view' );
		$diff = $this->context->getRequest()->getVal( 'action', -1 );

		if ( $action === 'view' && $diff !== -1 ) {
			return true;
		}

		return false;
	}

}
