<?php

namespace BlueSpice\Calumma\Tests\BreadcrumbRootNode;

use BlueSpice\Calumma\BreadcrumbRootNode\SpecialAllPages;
use BlueSpice\Calumma\IBreadcrumbRootNode;
use HashConfig;
use PHPUnit\Framework\TestCase;
use SpecialPageFactory;
use Title;

/**
 * @group BlueSpice
 * @group BlueSpiceCalumma
 */
class SpecialAllPagesTest extends TestCase {

	/**
	 * @covers SpecialAllPages::factory
	 */
	public function testFactory() {
		$config = new HashConfig( [] );

		$node = SpecialAllPages::factory( $config );

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

		$node = SpecialAllPages::factory( $config );

		$title = SpecialPageFactory::getTitleForAlias( 'Allpages' );

		$html = $node->getHtml( $title );

		$this->assertEmpty( $html, 'Should be empty' );
	}

	/**
	 * @covers SpecialAllPages::getHtml
	 */
	public function testGetHtmlOnWikiPage() {
		$config = new HashConfig( [] );

		$node = SpecialAllPages::factory( $config );

		$title = Title::makeTitle( NS_HELP, 'SomePage' );
		$specialAllPages = SpecialPageFactory::getTitleForAlias( 'Allpages' );

		$html = $node->getHtml( $title );

		$this->assertNotEmpty( $html, 'Should not be empty' );
		$this->assertContains(
			$specialAllPages->getPrefixedText(),
			$html,
			'Should have namespace prefix'
		);
	}
}
