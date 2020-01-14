<?php

namespace BlueSpice\Calumma\Tests\BreadcrumbRootNode;

use BlueSpice\Calumma\BreadcrumbRootNode\NamespaceMainPage;
use BlueSpice\Calumma\IBreadcrumbRootNode;
use HashConfig;
use PHPUnit\Framework\TestCase;
use SpecialPageFactory;
use Title;

/**
 * @group BlueSpice
 * @group BlueSpiceCalumma
 */
class NamespaceMainPageTest extends TestCase {

	/**
	 * @covers NamespaceMainPage::factory
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
	 * @covers NamespaceMainPage::getHtml
	 */
	public function testGetHtmlOnSpecialPage() {
		$config = new HashConfig( [] );

		$node = NamespaceMainPage::factory( $config );

		$title = SpecialPageFactory::getTitleForAlias( 'Allpages' );

		$html = $node->getHtml( $title );

		$this->assertEmpty( $html, 'Should be empty' );
	}

	/**
	 * @covers NamespaceMainPage::getHtml
	 */
	public function testGetHtmlOnWikiPage() {
		$config = new HashConfig( [] );

		$node = NamespaceMainPage::factory( $config );

		$title = Title::makeTitle( NS_HELP, 'SomePage' );

		$html = $node->getHtml( $title );

		$this->assertNotEmpty( $html, 'Should not be empty' );
		$this->assertContains( $title->getNsText() . ':', $html, 'Should have namespace prefix' );
	}
}
