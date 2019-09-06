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
			'title' => Message::newFromKey( 'bs-calumma-context-pagebutton-tooltip' )->plain(),
			'text' => Message::newFromKey( 'bs-calumma-context-pagebutton-label' )->plain(),
			'href' => $this->context->getTitle()->getSubjectPage()->getLinkURL(),
			'hasItems' => !empty( $items ),
			'items' => $items,
			'class' => 'btn'
		];
		$splitButton = new SplitButtonDropdown( null, $data );

		$this->html .= $splitButton->getHtml();
	}

	private function makeDiscussionButton() {
		$this->html .= $this->linkRenderer->makeLink(
			$this->getContext()->getTitle()->getTalkPage(),
			Message::newFromKey( 'bs-calumma-context-discussionbutton-label' )->plain(),
			[
				'id' => 'bs-page-discussion-button',
				'title' => Message::newFromKey( 'bs-calumma-context-discussionbutton-tooltip' )
					->plain(),
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
