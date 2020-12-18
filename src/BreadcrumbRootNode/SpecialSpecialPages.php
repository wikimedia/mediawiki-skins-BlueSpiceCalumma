<?php

namespace BlueSpice\Calumma\BreadcrumbRootNode;

use BlueSpice\Calumma\BreadcrumbRootNodeBase;
use SpecialPageFactory;
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

		$specialSpecialpages = SpecialPageFactory::getTitleForAlias( 'Specialpages' );

		return $this->linkRenderer->makeLink(
			$specialSpecialpages,
			$specialSpecialpages->getNsText(),
			[
				'class' => 'btn',
				'role' => 'button'
			]
		);
	}

}
