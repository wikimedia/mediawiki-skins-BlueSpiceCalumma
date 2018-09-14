<?php

namespace BlueSpice\Calumma\Panel;

use BlueSpice\Calumma\Components\SimpleLinkListGroup;
use BlueSpice\Calumma\Components\CollapsibleGroup;

class MediaWikiSidebar extends BasePanel {

	/**
	 *
	 * @var \SkinTemplate
	 */
	protected $skintemplate = null;

	/**
	 *
	 * @param SkinTemplate $skintemplate
	 */
	public function __construct( $skintemplate ) {
		$this->skintemplate = $skintemplate;
	}

	/**
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return empty( $this->skintemplate->get( 'sidebar' ) );
	}

	/**
	 *
	 * @return string
	 */
	public function getBody() {
		$data = $this->skintemplate->get( 'sidebar' );
		$this->shiftBlueSpiceAboutToEnd( $data['navigation'] );
		$this->extendDefaultNavigation( $data['navigation'] );

		$html = '';

		$navLinks = [];
		if ( $this->skintemplate->getSkin()->getUser()->isAllowed( 'editinterface' ) ) {
			$sidebar = \Title::makeTitle( NS_MEDIAWIKI, 'Sidebar' );
			$navLinks[] = [
				'text' => '',
				'href' => $sidebar->getEditURL(),
				'title' => '',
				'target' => '_blank',
				'class' => 'bs-link-edit-mediawiki-sidebar',
				'iconClass' => 'bs-icon-edit-mediawiki-sidebar'
			];
		}
		$navLinks += $data['navigation'];

		$navigation = new SimpleLinkListGroup( $navLinks );
		$html .= $navigation->getHtml();
		foreach ( $data as $section => $links ) {
			if ( $this->skipSection( $section ) ) {
				continue;
			}

			$message = wfMessage( $section );

			if ( $message->exists() ) {
				$section = $message->plain();
			}

			$linklistgroup = new SimpleLinkListGroup( $links );

			$sectionId = str_replace( ' ', '-', $section );

			$collapsibleGroup = new CollapsibleGroup( [
				'id' => $sectionId,
				'title' => $section,
				'content' => $linklistgroup->getHtml()
			] );

			$html .= $collapsibleGroup->getHtml();
		}

		return $html;
	}

	/**
	 *
	 * @return string
	 */
	public function getHtmlId() {
		return 'bs-mediawiki-sidebar';
	}

	/**
	 *
	 * @return \Message
	 */
	public function getTitleMessage() {
		return wfMessage( 'bs-sitenav-navigation-section-mediawikisidebar' );
	}

	protected $sectionsToSkip = [ 'navigation', 'SEARCH', 'TOOLBOX', 'LANGUAGES' ];

	/**
	 *
	 * @param string $section
	 * @return bool
	 */
	protected function skipSection( $section ) {
		if ( in_array( $section, $this->sectionsToSkip ) ) {
			return true;
		}
		return false;
	}

	/**
	 *
	 * @param array &$data
	 */
	protected function extendDefaultNavigation( &$data ) {
		foreach ( $data as &$link ) {
			if ( !isset( $link['title'] ) ) {
				$link['title'] = $link['text'];
			}
			if ( ( $link['id'] === 'n-recentchanges' )
					|| ( $link['id'] === 'n-special-recentchanges' ) ) {

				$introText = wfMessage( 'bs-calumma-recentchanges-intro' )->plain() . '</br>';
				$introText .= wfMessage( 'bs-calumma-recentchanges-specialpage-link' )->parse();

				$link['href'] = '#';
				$link['data'] = [
					[
						'key' => 'trigger-callback',
						'value' => 'bs.calumma.recentchanges.flyoutTriggerCallback'
					],
					[
						'key' => 'trigger-rl-deps',
						'value' => 'skin.bluespicecalumma.flyout.recentchanges'
					],
					[
						'key' => 'flyout-title',
						'value' => wfMessage( 'bs-calumma-recentchanges-title' )->plain()
					],
					[
						'key' => 'flyout-intro',
						'value' => $introText
					]
				];
			}
		}
	}

	private function shiftBlueSpiceAboutToEnd( &$data ) {
		$aboutLink = [];
		$DataLinks = [];
		foreach ( $data as &$link ) {
			if ( ( $link['id'] === 'n-bluespiceabout' ) ) {
				$aboutLink = $link;
			} else {
				$DataLinks[] = $link;
			}
		}

		if ( !empty( $aboutLink ) ) {
			array_push( $DataLinks, $aboutLink );
			$data = $DataLinks;
		}
	}
}
