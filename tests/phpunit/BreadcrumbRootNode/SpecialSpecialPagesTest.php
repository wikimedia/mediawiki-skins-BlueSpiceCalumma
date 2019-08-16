<?php

namespace BlueSpice\Calumma\Tests\BreadcrumbRootNode;

use PHPUnit\Framework\TestCase;
use BlueSpice\Calumma\BreadcrumbRootNode\SpecialSpecialPages;
use BlueSpice\Calumma\IBreadcrumbRootNode;
use HashConfig;
use SpecialPageFactory;
use Title;

/**
 * @group BlueSpice
 * @group BlueSpiceCalumma
 */
class SpecialSpecialPagesTest extends TestCase {

	/**
	 * @covers SpecialSpecialPages::factory
	 */
	public function testFactory() {
		$config = new HashConfig( [] );

		$node = SpecialSpecialPages::factory( $config );

		$this->assertInstanceOf(
			IBreadcrumbRootNode::class,
			$node,
			"Should implement IBreadcrumbRootNode"
		);
	}

	/**
	 * @covers SpecialAllPages::getHtml
	 */
	public function testGetHtmlOnSpecialPage() {
		$config = new HashConfig( [] );

		$node = SpecialSpecialPages::factory( $config );

		$title = SpecialPageFactory::getTitleForAlias( 'Allpages' );
		$specialSpecialPages = SpecialPageFactory::getTitleForAlias( 'Specialpages' );

		$html = $node->getHtml( $title );

		$this->assertNotEmpty( $html, 'Should not be empty' );
		$this->assertContains(
			$specialSpecialPages->getPrefixedText(),
			$html,
			'Should have namespace prefix'
		);
	}

	/**
	 * @covers SpecialAllPages::getHtml
	 */
	public function testGetHtmlOnWikiPage() {
		$config = new HashConfig( [] );

		$node = SpecialSpecialPages::factory( $config );

		$title = Title::makeTitle( NS_HELP, 'SomePage' );

		$html = $node->getHtml( $title );

		$this->assertEmpty( $html, 'Should be empty' );
	}
}
