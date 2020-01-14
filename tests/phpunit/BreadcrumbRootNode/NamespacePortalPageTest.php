<?php

namespace BlueSpice\Calumma\Tests\BreadcrumbRootNode;

use BlueSpice\Calumma\BreadcrumbRootNode\NamespacePortalPage;
use BlueSpice\Calumma\IBreadcrumbRootNode;
use HashConfig;
use PHPUnit\Framework\TestCase;
use SpecialPageFactory;
use Title;

/**
 * @group BlueSpice
 * @group BlueSpiceCalumma
 */
class NamespacePortalPageTest extends TestCase {

	/**
	 * @covers NamespacePortalPage::factory
	 */
	public function testFactory() {
		$config = new HashConfig( [] );

		$node = NamespacePortalPage::factory( $config );

		$this->assertInstanceOf(
			IBreadcrumbRootNode::class,
			$node,
			"Should implement IBreadcrumbRootNode"
		);
	}

	/**
	 * @covers NamespacePortalPage::getHtml
	 */
	public function testGetHtmlOnSpecialPage() {
		$config = new HashConfig( [
			'BlueSpiceCalummaBreadcrumbNamespacePortalPageRootNodePortalNamespace' => NS_PROJECT
		] );

		$node = NamespacePortalPage::factory( $config );

		$title = SpecialPageFactory::getTitleForAlias( 'Allpages' );

		$html = $node->getHtml( $title );

		$this->assertEmpty( $html, 'Should be empty' );
	}

	/**
	 * @covers NamespacePortalPage::getHtml
	 */
	public function testGetHtmlOnWikiPage() {
		$config = new HashConfig( [
			'BlueSpiceCalummaBreadcrumbNamespacePortalPageRootNodePortalNamespace' => NS_PROJECT
		] );

		$node = NamespacePortalPage::factory( $config );

		$title = Title::makeTitle( NS_HELP, 'SomePage' );
		$portalTitle = Title::makeTitle( NS_PROJECT, 'Dummy' );

		$html = $node->getHtml( $title );

		$this->assertNotEmpty( $html, 'Should not be empty' );
		$this->assertContains(
			$portalTitle->getNsText() . ':',
			$html,
			'Should have namespace prefix'
		);
	}
}
