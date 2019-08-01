<?php

namespace BlueSpice\Calumma\AlertProvider;

use BlueSpice\AlertProviderBase;
use BlueSpice\IAlertProvider;
use Message;

class OldVersion extends AlertProviderBase {

	/**
	 *
	 * @return string
	 */
	public function getHTML() {
		if ( $this->isOldVersion() ) {
			$message = Message::newFromKey( 'bs-calumma-alert-oldpage' );
			$message->params( $this->skin->getTitle()->getArticleID() );
			return $message->parse();
		}

		return '';
	}

	/**
	 *
	 * @return string
	 */
	public function getType() {
		return IAlertProvider::TYPE_INFO;
	}

	/**
	 *
	 * @return bool
	 */
	private function isOldVersion() {
		$oldId = $this->skin->getRequest()->getInt( 'oldid', -1 );
		$currentRevId = $this->skin->getTitle()->getLatestRevID();
		if ( $oldId !== -1 && $oldId !== $currentRevId ) {
			return true;
		}
		return false;
	}

}
