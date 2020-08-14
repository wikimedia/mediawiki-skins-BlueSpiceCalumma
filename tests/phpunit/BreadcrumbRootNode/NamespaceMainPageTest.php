<?php

namespace BlueSpice\Calumma\Tests\BreadcrumbRootNode;

use BlueSpice\Calumma\BreadcrumbRootNode\NamespaceMainPage;
use BlueSpice\Calumma\IBreadcrumbRootNode;
use HashConfig;
use MediaWikiTestCase;
use Title;

/**
 * @group BlueSpice
 * @group BlueSpiceCalumma
 */
class NamespaceMainPageTest extends MediaWikiTestCase {

	/**
	 * @covers \BlueSpice\Calumma\BreadcrumbRootNode\NamespaceMainPage::factory
	 */
	public function testFactory() {
		$config = new HashConfig( [] );

		$node = NamespaceMainPage::factory( $config );

		$this->assertInstanceOf(
			IBreadcrumbRootNode::class,
			$node,
			"Should implement IBreadcrumbRootNode"
		);
	}

	/**
	 * @covers \BlueSpice\Calumma\BreadcrumbRootNode\NamespaceMainPage::getHtml
	 */
	public function testGetHtmlOnSpecialPage() {
		$config = new HashConfig( [] );

		$node = NamespaceMainPage::factory( $config );

		$title = \MediaWiki\MediaWikiServices::getInstance()
			->getSpecialPageFactory()
			->getTitleForAlias( 'Allpages' );

		$html = $node->getHtml( $title );

		$this->assertEmpty( $html, 'Should be empty' );
	}

	/**
	 * @covers \BlueSpice\Calumma\BreadcrumbRootNode\NamespaceMainPage::getHtml
	 */
	public function testGetHtmlOnWikiPage() {
		$config = new HashConfig( [] );

		$node = NamespaceMainPage::factory( $config );

		$title = Title::makeTitle( NS_HELP, 'SomePage' );

		$html = $node->getHtml( $title );

		$this->assertNotEmpty( $html, 'Should not be empty' );
		$this->assertStringContainsString( $title->getNsText() . ':', $html, 'Should have namespace prefix' );
	}
}
