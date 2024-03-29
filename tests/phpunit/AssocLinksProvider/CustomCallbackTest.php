<?php

namespace BlueSpice\Calumma\Tests\AssocLinksProvider;

use BlueSpice\Calumma\AssocLinksProvider\CustomCallback;
use BlueSpice\Html\Descriptor\ILink;
use Exception;
use HashConfig;
use PHPUnit\Framework\TestCase;

/**
 * @group BlueSpice
 * @group BlueSpiceCalumma
 */
class CustomCallbackTest extends TestCase {

	/**
	 * @covers \BlueSpice\Calumma\AssocLinksProvider\CustomCallback::factory
	 */
	public function testFactory() {
		$callback = static function ( $context, $config ) {
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

		$this->assertCount( 1, $links, "Should create one item" );
		$this->assertInstanceOf( ILink::class, $links[0], "First item should implement ILink" );
		$this->assertEquals(
			'Some Label',
			$links[0]->getLabel()->plain(),
			"Should return proper label"
		);
	}

	/**
	 * @covers \BlueSpice\Calumma\AssocLinksProvider\CustomCallback::factory
	 */
	public function testFactoryException() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( "Field 'label' must be provided!" );
		$callback = static function ( $context, $config ) {
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
