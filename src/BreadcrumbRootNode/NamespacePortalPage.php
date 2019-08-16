<?php

namespace BlueSpice\Calumma\BreadcrumbRootNode;

use BlueSpice\Calumma\BreadcrumbRootNodeBase;
use Title;
use Message;

class NamespacePortalPage extends BreadcrumbRootNodeBase {

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	public function getHtml( $title ) {
		if ( $title->isSpecialPage() ) {
			return '';
		}

		$portalNamespace = $this->config->get(
			'BlueSpiceCalummaBreadcrumbNamespacePortalPageRootNodePortalNamespace'
		);

		$namespacePortalPage = Title::makeTitle(
			$portalNamespace,
			$title->getNsText()
		);

		$nsText = str_replace( '_', ' ', $title->getNsText() );
		if ( $title->getNamespace() === NS_MAIN ) {
			$nsText = Message::newFromKey( 'bs-ns_main' );
		}

		return $this->linkRenderer->makeLink(
			$namespacePortalPage,
			$nsText
		);
	}
}
