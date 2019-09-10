<?php

namespace BlueSpice\Calumma\Renderer\PageHeader;

use Html;
use Title;
use BlueSpice\Renderer;
use BlueSpice\Calumma\Controls\SplitButtonDropdown;
use BlueSpice\ExtensionAttributeBasedRegistry;
use BlueSpice\Calumma\IBreadcrumbRootNode;
use Exception;

class BreadCrumb extends Renderer {

	/**
	 * @return string
	 */
	public function render() {
		$html = '';
		$title = $this->getContext()->getTitle();
		if ( !$title ) {
			return $html;
		}
		if ( $title->isSpecialPage() ) {
			$title = $this->maybeSwapTitle( $title );
		}

		$html .= $this->makePortalLink( $title );
		$html .= $this->makeTitleLinks( $title );

		return $html;
	}

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	private function makePortalLink( Title $title ) {
		$registry = new ExtensionAttributeBasedRegistry(
			'BlueSpiceFoundationBreadcrumbRootNodeRegistry'
		);

		$breadcrumbRootNodes = [];
		foreach ( $registry->getAllValues() as $callbackKey => $callback ) {
			$breadcrumbRootNode = call_user_func_array( $callback, [ $this->config ] );
			if ( $breadcrumbRootNode instanceof IBreadcrumbRootNode === false ) {
				throw new Exception( "Factory callback for '$callbackKey' did not return a "
					. "IBreadcrumbRootNode object" );
			}

			$rootNodeHtml = $breadcrumbRootNode->getHtml( $title );
			if ( !empty( $rootNodeHtml ) ) {
				return Html::rawElement(
					'div',
					[
						'id' => 'bs-breadcrumb-rootnode'
					],
					"$rootNodeHtml"
				);
			}
		}

		return '';
	}

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	private function makeTitleLinks( Title $title ) {
		$titleParts = explode( '/', $title->getText() );
		$splitButtons = [];

		$root = '';
		foreach ( $titleParts as $titlePart ) {
			$root .= $titlePart;
			$part = Title::newFromText( $root, $title->getNamespace() );
			$root .= '/';

			$path = '';
			if ( $part->getNamespace() === NS_MAIN ) {
				$path = ':';
			}
			$path .= $part->getPrefixedDBkey();
			$classes = [ 'bs-breadcrumb-node' ];
			if ( !$part->exists() && !$part->isSpecialPage() ) {
				$classes[] = 'new';
			}

			$splitButtons[] = new SplitButtonDropdown( null, [
				'classes' => $classes,
				'href' => $part->getLinkURL(),
				'text' => $part->getSubpageText(),
				'title' => $part->getPrefixedText(),
				// We assume that _all_ nodes have subpages, as also non existing ones will be listed
				// Only the leaf node must be checked explicitly
				'hasItems' => $title->equals( $part ) ? $part->hasSubpages() : true,
				'data' => [
					[
						'key' => 'bs-path',
						'value' => $path
					]
				]
			] );
		}

		$html = [];
		foreach ( $splitButtons as $splitButton ) {
			$html[] = $splitButton->getHtml();
		}

		$seperator = '';

		return implode( $seperator, $html );
	}

	/**
	 *
	 * @param Title $title
	 */
	private function maybeSwapTitle( $title ) {
		// e.g. "Special:MovePage/Help:Some/Page/With/Subpage"
		$titleParts = explode( '/', $title->getPrefixedText(), 2 );

		if ( isset( $titleParts[1] ) ) {
			// e.g. "Help:Some/Page/With/Subpage"
			$title = Title::newFromText( $titleParts[1] );
		}

		return $title;
	}

}
