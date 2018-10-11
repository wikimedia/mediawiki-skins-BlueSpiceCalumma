<?php

namespace BlueSpice\Calumma\Panel;

class Toolbox extends BasePanel {

	/**
	 *
	 * @return \Message
	 */
	public function getTitleMessage() {
		return new \Message( 'bs-sitetools-toolbox' );
	}

	/**
	 *
	 * @return string
	 */
	public function getBody() {
		$toolbox = $this->skintemplate->getToolbox();

		$linkDefs = $this->makeLinkDefs( $toolbox );

		$links = new LinkList( 'dummy-id', [
			'content' => $linkDefs
		] );

		return $links->getBody();
	}

	private function makeLinkDefs( $toolbox ) {
		$linkDefs = [];
		foreach ( $toolbox as $toolboxKey => $linkDesc ) {
			if ( empty( $linkDesc['text'] ) ) {
				$linkDesc['text'] = wfMessage( $toolboxKey )->text();
			}

			$linkDef = [
				'id' => $linkDesc['id'],
				'title' => $linkDesc['id'],
				'text' => $linkDesc['text'],
				'href' => $linkDesc['href'],
			];

			$linkDefs[] = $linkDef;
		}
		return $linkDefs;
	}

}
