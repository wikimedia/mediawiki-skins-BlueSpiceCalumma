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

		if ( $oldId !== -1 ) {
			$currentRevId = $this->skin->getTitle()->getLatestRevID();
			$direction = $this->skin->getRequest()->getVal( 'direction', '' );
			if ( $direction === 'next' ) {
				$oldId = $this->skin->getTitle()->getNextRevisionID( $oldId );
			}
			if ( $direction === 'prev' ) {
				$oldId = $this->skin->getTitle()->getPreviousRevisionID( $oldId );
			}

			if ( $oldId !== $currentRevId ) {
				return true;
			}
		}
		return false;
	}

}
