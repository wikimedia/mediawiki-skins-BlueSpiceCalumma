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
		$url = $this->getTitle()->getSubjectPage()->getLocalURL();
		return $url;
	}

	/**
	 *
	 * @return bool
	 */
	protected function skipProcessing() {
		return $this->getTitle()->isTalkPage() === false;
	}

}
