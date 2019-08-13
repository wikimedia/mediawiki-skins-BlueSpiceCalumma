<?php

namespace BlueSpice\Calumma\Renderer\PageHeader;

use BlueSpice\Renderer;
use BlueSpice\ExtensionAttributeBasedRegistry;
use BlueSpice\Calumma\AssocLinksCollector;
use BlueSpice\Calumma\Controls\SplitButtonDropdown;
use Message;

class Context extends Renderer {

	protected $html = '';

	public function render() {
		if ( $this->getContext()->getTitle()->isSpecialPage() ) {
			return '';
		}

		$this->makePageButton();
		$this->makeDiscussionButton();
		return $this->html;
	}

	private function makePageButton() {
		$items = $this->getAssocLinks();
		$data = [
			'id' => 'bs-page-context-button',
			// TODO: Use dedicated message key
			'title' => Message::newFromKey( 'bs-extjs-label-page' )->plain(),
			// TODO: Use dedicated message key
			'text' => Message::newFromKey( 'bs-extjs-label-page' )->plain(),
			'href' => $this->context->getTitle()->getSubjectPage()->getLinkURL(),
			'hasItems' => !empty( $items ),
			'items' => $items
		];
		$splitButton = new SplitButtonDropdown( null, $data );

		$this->html .= $splitButton->getHtml();
	}

	private function makeDiscussionButton() {
		$this->html .= $this->linkRenderer->makeLink(
			$this->getContext()->getTitle()->getTalkPage(),
			// TODO: Use dedicated message key
			Message::newFromKey( 'bs-calumma-pagetool-talk-tooltip' )->plain(),
			[
				'id' => 'bs-page-discussion-button'
			]
		);
	}

	private function getAssocLinks() {
		$linkDefs = [];

		$registry = new ExtensionAttributeBasedRegistry(
			'BlueSpiceFoundationAssocLinksProviderRegistry'
		);
		$collector = new AssocLinksCollector( $registry, $this->context, $this->config );

		foreach ( $collector->getLinkDefs() as $linkDef ) {
			$linkDefs[] = [
				'link' => $linkDef
			];
		}

		return $linkDefs;
	}

}
