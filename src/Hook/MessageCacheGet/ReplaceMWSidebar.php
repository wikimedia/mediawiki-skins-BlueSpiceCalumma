<?php

namespace BlueSpice\Calumma\Hook\MessageCacheGet;

use BlueSpice\Hook\MessageCacheGet;

class ReplaceMWSidebar extends MessageCacheGet {

	protected function skipProcessing() {
		if ( $this->lckey === 'sidebar' ) {
			return false;
		}
		return true;
	}

	protected function doProcess() {
		$this->lckey = 'bs-sidebar-override';
		return true;
	}
}
