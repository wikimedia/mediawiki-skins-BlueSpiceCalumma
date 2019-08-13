<?php

namespace BlueSpice\Calumma\AssocLinksProvider;

use BlueSpice\Html\Descriptor\TitleLink;
use Message;

class HistoryView extends TitleLink {

	/**
	 *
	 * @return \Message
	 */
	public function getLabel() {
		// TODO: Use dedicated message key
		return Message::newFromKey( 'bs-calumma-pagetool-history-tooltip' );
	}

	/**
	 *
	 * @return Message
	 */
	public function getTooltip() {
		// TODO: Use dedicated message key
		return Message::newFromKey( 'history-title' )->params( $this->title->getPrefixedText() );
	}

	/**
	 *
	 * @return string
	 */
	public function getHref() {
		return $this->title->getLinkURL( [ 'action' => 'history' ] );
	}

	/**
	 *
	 * @param \IContextSource $context
	 * @param \Config $config
	 * @return ILink[]
	 */
	public static function factory( $context, $config ) {
		if ( $context->getRequest()->getVal( 'action', 'view' ) === 'history' ) {
			return [];
		}
		if ( !$context->getTitle()->exists() ) {
			return [];
		}

		return [ 'history' => new static( $context, $config ) ];
	}
}
