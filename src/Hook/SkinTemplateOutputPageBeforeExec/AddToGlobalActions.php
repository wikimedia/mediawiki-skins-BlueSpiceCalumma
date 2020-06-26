<?php

namespace BlueSpice\Calumma\Hook\SkinTemplateOutputPageBeforeExec;

use BlueSpice\Hook\SkinTemplateOutputPageBeforeExec;
use BlueSpice\SkinData;
use SpecialPageFactory;

class AddToGlobalActions extends SkinTemplateOutputPageBeforeExec {

	protected function doProcess() {
		$this->addSpecialPages();
		$this->addSpecialUpload();
		$this->addSpecialWatchlist();
		return true;
	}

	private function addSpecialPages() {
		$special = SpecialPageFactory::getPage( 'Specialpages' );
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
		$special = SpecialPageFactory::getPage( 'Upload' );
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
		$special = SpecialPageFactory::getPage( 'Watchlist' );
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
