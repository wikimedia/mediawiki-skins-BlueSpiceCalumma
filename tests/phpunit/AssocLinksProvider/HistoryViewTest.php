<?php

namespace BlueSpice\Calumma\Tests\AssocLinksProvider;

use PHPUnit\Framework\TestCase;
use BlueSpice\Calumma\AssocLinksProvider\HistoryView;
use BlueSpice\Html\Descriptor\ILink;
use HashConfig;
use IContextSource;
use WebRequest;
use Title;

class HistoryViewTest extends TestCase {

	/**
	 * @covers HistoryView::factory
	 */
	public function testFactory() {
		$requestMock = $this->createMock( WebRequest::class );
		$requestMock->method( 'getVal' )
			->willReturn( '' );

		$mainPageTitle = Title::newMainPage();

		$contextMock = $this->createMock( IContextSource::class );
		$contextMock->expects( $this->any() )
			->method( 'getRequest' )
			->willReturn( $requestMock );
		$contextMock->expects( $this->any() )
			->method( 'getTitle' )
			->willReturn( $mainPageTitle );

		$config = new HashConfig( [] );

		$links = HistoryView::factory( $contextMock, $config );

		$this->assertEquals( 1, count( $links ), "Should create one item" );
		$this->assertInstanceOf( ILink::class, $links[0],  "First item should implement ILink" );
		$this->assertContains(
			'action=history',
			$links[0]->getHref(),
			"Should return proper querystring parameter"
		);
	}

	/**
	 * @covers HistoryView::factory
	 */
	public function testFactoryEmptyResultBecauseOfAction() {
		$requestMock = $this->createMock( WebRequest::class );
		$requestMock->method( 'getVal' )
			->willReturn( 'history' );

		$mainPageTitle = Title::newMainPage();

		$contextMock = $this->createMock( IContextSource::class );
		$contextMock->expects( $this->any() )
			->method( 'getRequest' )
			->willReturn( $requestMock );
		$contextMock->expects( $this->any() )
			->method( 'getTitle' )
			->willReturn( $mainPageTitle );

		$config = new HashConfig( [] );

		$links = HistoryView::factory( $contextMock, $config );

		$this->assertEquals( 0, count( $links ), "Should not contain an item" );
	}

	/**
	 * @covers HistoryView::factory
	 */
	public function testFactoryEmptyResultBecauseOfNonExistingTitle() {
		$requestMock = $this->createMock( WebRequest::class );
		$requestMock->method( 'getVal' )
			->willReturn( '' );

		$title = Title::newFromText( 'Rand' . time() );

		$contextMock = $this->createMock( IContextSource::class );
		$contextMock->expects( $this->any() )
			->method( 'getRequest' )
			->willReturn( $requestMock );
		$contextMock->expects( $this->any() )
			->method( 'getTitle' )
			->willReturn( $title );

		$config = new HashConfig( [] );

		$links = HistoryView::factory( $contextMock, $config );

		$this->assertEquals( 0, count( $links ), "Should not contain an item" );
	}

	/**
	 * @covers HistoryView::factory
	 */
	public function testFactoryEmptyResultBecauseOfSpecialPage() {
		$requestMock = $this->createMock( WebRequest::class );
		$requestMock->method( 'getVal' )
			->willReturn( '' );

		$title = Title::makeTitle( NS_SPECIAL, 'Allpages' );

		$contextMock = $this->createMock( IContextSource::class );
		$contextMock->expects( $this->any() )
			->method( 'getRequest' )
			->willReturn( $requestMock );
		$contextMock->expects( $this->any() )
			->method( 'getTitle' )
			->willReturn( $title );

		$config = new HashConfig( [] );

		$links = HistoryView::factory( $contextMock, $config );

		$this->assertEquals( 0, count( $links ), "Should not contain an item" );
	}
}
