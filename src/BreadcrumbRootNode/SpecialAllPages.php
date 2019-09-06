<?php

namespace BlueSpice\Calumma\BreadcrumbRootNode;

use BlueSpice\Calumma\BreadcrumbRootNodeBase;
use SpecialPageFactory;
use Title;

class SpecialAllPages extends BreadcrumbRootNodeBase {

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	public function getHtml( $title ) {
		if ( $title->isSpecialPage() ) {
			return '';
		}

		$specialAllpages = SpecialPageFactory::getTitleForAlias( 'Allpages' );

		return $this->linkRenderer->makeLink(
			$specialAllpages,
			$specialAllpages->getText(),
			[
				'class' => 'btn',
				'role' => 'button'
			],
			[
				'namespace' => $title->getNamespace()
			]
		);
	}

}
