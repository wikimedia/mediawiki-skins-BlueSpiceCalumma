<?php

namespace BlueSpice\Calumma\Components;

use BlueSpice\Calumma\PanelFactory;
use Skins\Chameleon\IdRegistry;
use BlueSpice\SkinData;

class ToolPaneTabs extends \BlueSpice\Calumma\Structure\TabPanelStructure {

	/**
	 *
	 * @return array
	 */
	protected function getSubcomponentsData() {
		$panelFactory = new PanelFactory(
			$this->getSkinTemplate()->get( SkinData::SITE_TOOLS ),
			$this->getSkinTemplate()
		);

		$panels = $panelFactory->makePanels();
		$subComponentsData = [];
		foreach ( $panels as $panel ) {
			if ( !$panel->shouldRender( $this->getSkin()->getContext() ) ) {
				continue;
			}
			$panel instanceof \BlueSpice\Calumma\IPanel;
			$activeTabId = $this->getActiveTabId();
			$tabId = $panel->getHtmlId();
			$subComponentsData[] = [
				'id' => $tabId,
				'active' => $tabId === $activeTabId,
				'title' => $panel->getTitleMessage(),
				'body' => $panel->getBody()
			];
		}

		return $subComponentsData;
	}

	/**
	 * The HTML ID for this component
	 * @return string
	 */
	public function getHtmlId() {
		if ( $this->htmlId === null ) {
			$this->htmlId = IdRegistry::getRegistry()->getId( 'bs-toolpanetabs' );
		}
		return $this->htmlId;
	}
}
