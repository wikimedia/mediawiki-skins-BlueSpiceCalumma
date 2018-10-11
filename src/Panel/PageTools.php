<?php

namespace BlueSpice\Calumma\Panel;

use BlueSpice\SkinData;
use BlueSpice\Calumma\PanelFactory;

class PageTools extends PanelContainer {

	/**
	 *
	 * @return string
	 */
	public function getHtmlId() {
		return 'bs-pagetools-panel';
	}

	/**
	 *
	 * @return \Message
	 */
	public function getTitleMessage() {
		return wfMessage( 'bs-sitetools-pagetools-title' );
	}

	/**
	 *
	 * @return \BlueSpice\Calumma\IPanel[]
	 */
	protected function makePanels() {
		$defaultPanelDefs = [
			// Disabled for BETA release, as long a this structure is a mess
			/*'contentactions' => [
				'position' => 20,
				'callback' => function( $sktemplate ) {
					return new ContentActions( $sktemplate );
				}
			],*/
			'toolbox' => [
				'position' => 20,
				'callback' => function ( $sktemplate ) {
					return new Toolbox( $sktemplate );
				}
			],
			'export' => [
				'position' => 100,
				'callback' => function ( $sktemplate ) {
					return new Export( $sktemplate );
				}
			]
		];
		$panelDefs = $this->skintemplate->get( SkinData::PAGE_TOOLS_PANEL );

		$combinedPanelDefs = array_merge_recursive(
			$defaultPanelDefs,
			$panelDefs
		);

		$panelFactory = new PanelFactory(
			$combinedPanelDefs,
			$this->skintemplate
		);

		return $panelFactory->makePanels();
	}

}
