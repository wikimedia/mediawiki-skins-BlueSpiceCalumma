<?php

namespace BlueSpice\Calumma\Hook\SkinTemplateOutputPageBeforeExec;

use BlueSpice\Hook\SkinTemplateOutputPageBeforeExec;
use BlueSpice\SkinData;

class AddToGlobalActions extends SkinTemplateOutputPageBeforeExec {

	protected function doProcess() {
		$this->addSpecialPages();
		$this->addSpecialUpload();
		$this->addSpecialWatchlist();
		return true;
	}

	private function addSpecialPages() {
		$factory = $this->getServices()->getSpecialPageFactory();
		$special = $factory->getPage( 'Specialpages' );
		if ( !$special ) {
			return;
		}
		if ( !$this->getContext()
			->getUser()
			->isAllowed( $special->getRestriction() )
		) {
			return;
		}
		$this->mergeSkinDataArray(
			SkinData::GLOBAL_ACTIONS,
			[
				'specialpage-specialpages' => [
					'href' => $special->getPageTitle()->getFullURL(),
					'text' => $special->getDescription(),
					'title' => $special->getPageTitle(),
					'iconClass' => ' icon-special-specialpages ',
					'position' => 20
				]
			]
		);
	}

	private function addSpecialUpload() {
		$factory = $this->getServices()->getSpecialPageFactory();
		$special = $factory->getPage( 'Upload' );
		if ( !$special ) {
			return;
		}
		if ( !$this->getContext()
			->getUser()
			->isAllowed( $special->getRestriction() )
		) {
			return;
		}
		$this->mergeSkinDataArray(
			SkinData::GLOBAL_ACTIONS,
			[
				'specialpage-upload' => [
					'href' => $special->getPageTitle()->getFullURL(),
					'text' => $special->getDescription(),
					'title' => $special->getPageTitle(),
					'iconClass' => ' icon-special-upload ',
					'position' => 10
				]
			]
		);
	}

	private function addSpecialWatchlist() {
		$factory = $this->getServices()->getSpecialPageFactory();
		$special = $factory->getPage( 'Watchlist' );
		if ( !$special ) {
			return;
		}

		if ( $this->getContext()->getUser()->isAnon() ) {
			return;
		}

		if ( !$this->getContext()
			->getUser()
			->isAllowed( $special->getRestriction() )
		) {
			return;
		}
		$this->mergeSkinDataArray(
			SkinData::GLOBAL_ACTIONS,
			[
				'specialpage-watchlist' => [
					'href' => $special->getPageTitle()->getFullURL(),
					'text' => $special->getDescription(),
					'title' => $special->getPageTitle(),
					'iconClass' => ' icon-special-watchlist ',
					'position' => 30
				]
			]
		);
	}
}
