<?php

namespace BlueSpice\Calumma\BreadcrumbRootNode;

use BlueSpice\Calumma\BreadcrumbRootNodeBase;
use Message;
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

		$nsText = str_replace( '_', ' ', $title->getNsText() );
		if ( $title->getNamespace() === NS_MAIN ) {
			$nsText = Message::newFromKey( 'bs-ns_main' );
		}

		$specialAllpages = \MediaWiki\MediaWikiServices::getInstance()
			->getSpecialPageFactory()
			->getTitleForAlias( 'Allpages' );

		return $this->linkRenderer->makeLink(
			$specialAllpages,
			$nsText,
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
