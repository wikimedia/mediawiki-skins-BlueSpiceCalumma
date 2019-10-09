<?php

namespace BlueSpice\Calumma\Tests\AssocLinksProvider;

use PHPUnit\Framework\TestCase;
use BlueSpice\Calumma\AssocLinksProvider\CustomCallback;
use BlueSpice\Html\Descriptor\ILink;
use HashConfig;

/**
 * @group BlueSpice
 * @group BlueSpiceCalumma
 */
class CustomCallbackTest extends TestCase {

	/**
	 * @covers CustomCallback::factory
	 */
	public function testFactory() {
		$callback = function ( $context, $config ) {
			return [
				[
					'href' => '#',
					'label' => 'Some Label',
					'tooltip' => 'Some tooltip'
				]
			];
		};

		$config = new HashConfig( [
			'BlueSpiceCalummaAssocLinksCustomCallback' => $callback
		] );
		$links = CustomCallback::factory( null, $config );

		$this->assertEquals( 1, count( $links ), "Should create one item" );
		$this->assertInstanceOf( ILink::class, $links[0],  "First item should implement ILink" );
		$this->assertEquals(
			'Some Label',
			$links[0]->getLabel()->plain(),
			"Should return proper label"
		);
	}

	/**
	 * @covers CustomCallback::factory
	 * @expectedException Exception
	 * @expectedExceptionMessage Field 'label' must be provided!
	 */
	public function testFactoryException() {
		$callback = function ( $context, $config ) {
			return [
				[
					'href' => '#',
					'tooltip' => 'Some tooltip'
				]
			];
		};

		$config = new HashConfig( [
			'BlueSpiceCalummaAssocLinksCustomCallback' => $callback
		] );
		$links = CustomCallback::factory( null, $config );
	}
}
