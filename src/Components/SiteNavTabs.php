<?php

namespace BlueSpice\Calumma\Components;

use BlueSpice\Calumma\PanelFactory;
use BlueSpice\Calumma\Structure\TabPanelStructure;
use BlueSpice\Calumma\IActiveStateProvider;
use Skins\Chameleon\IdRegistry;
use BlueSpice\SkinData;

class SiteNavTabs extends TabPanelStructure {

	/**
	 *
	 * @return array
	 */
	protected function getSubcomponentsData() {
		$activeTabId = $this->getActiveTabId();

		$panelFactory = new PanelFactory(
			$this->getSkinTemplate()->get( SkinData::SITE_NAV ),
			$this->getSkinTemplate()
		);

		$panels = $panelFactory->makePanels();

		foreach ( $panels as $panel ) {
			if ( $panel instanceof IActiveStateProvider ) {
				if ( $panel->isActive() ) {
					$activeTabId = $panel->getHtmlId();
				}
			}
		}

		$subComponentsData = [];
		foreach ( $panels as $panel ) {
			if ( !$panel->shouldRender( $this->getSkin()->getContext() ) ) {
				continue;
			}

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
