<?php

namespace BlueSpice\Calumma\AssocLinksProvider;

use BlueSpice\Html\Descriptor\TitleLink;
use BlueSpice\Services;
use Message;
use RawMessage;
use Title;

class ExistingSiblings extends TitleLink {

	/**
	 *
	 * @return Message
	 */
	public function getLabel() {
		if ( $this->title->getNamespace() === NS_MAIN ) {
			return Message::newFromKey( 'bs-ns_main' );
		}
		return new RawMessage( $this->title->getNsText() );
	}

	/**
	 *
	 * @param \IContextSource $context
	 * @param \Config $config
	 * @return ILink[]
	 */
	public static function factory( $context, $config ) {
		$currentTitle = $context->getTitle();
		$namespaceBlacklist = $config->get(
			'BlueSpiceCalummaAssocLinksExistingSiblingsNamespaceBlacklist'
		);
		$dbr = Services::getInstance()->getDBLoadBalancer()->getConnection( DB_REPLICA );
		$res = $dbr->select(
			'page',
			'*',
			[
				'page_title' => $currentTitle->getDBkey()
			]
		);

		$links = [];
		foreach ( $res as $row ) {
			$assocTitle = Title::newFromRow( $row );
			if ( $assocTitle->equals( $currentTitle ) ) {
				continue;
			}
			if ( in_array( $assocTitle->getNamespace(), $namespaceBlacklist ) ) {
				continue;
			}
			$links["existing-namespace-{$assocTitle->getNamespace()}"]
				= new static( $context, $config, $assocTitle );
		}

		return $links;
	}
}
