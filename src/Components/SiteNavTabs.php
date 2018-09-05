<?php

namespace BlueSpice\Calumma\Components;

use BlueSpice\Calumma\PanelFactory;
use Skins\Chameleon\IdRegistry;
use BlueSpice\SkinData;

class SiteNavTabs extends \BlueSpice\Calumma\Structure\TabPanelStructure {

	/**
	 *
	 * @return array
	 */
	protected function getSubcomponentsData() {
		$panelFactory = new PanelFactory(
			$this->getSkinTemplate()->get( SkinData::SITE_NAV ),
			$this->getSkinTemplate()
		);

		$panels = $panelFactory->makePanels();
		$subComponentsData = [];
		foreach ( $panels as $key => $panel ) {
			if ( !$panel->shouldRender( $this->getSkin()->getContext() ) ) {
				continue;
			}
			$activeTabId = $this->getActiveTabId();
			$tabId = $panel->getHtmlId();
			$subComponentsData[] = [
				'id' => $tabId,
				'active' => $tabId === $activeTabId,
				'title' => $panel->getTitleMessage(),
				'body' => $panel->getBody(),
				'class' => $panel->getContainerClasses()
			];
		}

		return $subComponentsData;
	}

	/**
	 * The HTML ID for thie component
	 * @return string
	 */
	public function getHtmlId() {
		if ( $this->htmlId === null ) {
			$this->htmlId = IdRegistry::getRegistry()->getId( 'bs-sitenavtabs' );
		}
		return $this->htmlId;
	}
}
