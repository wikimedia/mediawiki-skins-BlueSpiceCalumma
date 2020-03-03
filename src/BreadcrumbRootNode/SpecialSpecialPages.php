<?php

namespace BlueSpice\Calumma\BreadcrumbRootNode;

use BlueSpice\Calumma\BreadcrumbRootNodeBase;
use Title;

class SpecialSpecialPages extends BreadcrumbRootNodeBase {

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	public function getHtml( $title ) {
		if ( !$title->isSpecialPage() ) {
			return '';
		}

		$specialSpecialpages = \MediaWiki\MediaWikiServices::getInstance()
			->getSpecialPageFactory()
			->getTitleForAlias( 'Specialpages' );
		if ( $title->equals( $specialSpecialpages ) ) {
			return '';
		}

		return $this->linkRenderer->makeLink(
			$specialSpecialpages,
			$specialSpecialpages->getText(),
			[
				'class' => 'btn',
				'role' => 'button'
			]
		);
	}

}
