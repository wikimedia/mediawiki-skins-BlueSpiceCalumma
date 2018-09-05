<?php
namespace BlueSpice\Calumma\DataProvider;

use BlueSpice\SkinData;
use BlueSpice\Calumma\SkinDataFieldDefinition as SDFD;

class SiteToolsData {

	/**
	 *
	 * @param \Skin $skin
	 * @param \SkinTemplate &$skintemplate
	 * @param array &$data
	 */
	public static function populate( $skin, &$skintemplate, &$data ) {
		self::populatePanels( $skin, $skintemplate, $data );
	}

	/**
	 *
	 * @param \Skin $skin
	 * @param \SkinTemplate &$skintemplate
	 * @param array &$data
	 */
	public static function populatePanels( $skin, &$skintemplate, &$data ) {
		self::polulatePageInfosPanel( $skin, $skintemplate, $data );
		self::polulatePageDocumentsPanel( $skin, $skintemplate, $data );
		self::polulatePageSettingsPanel( $skin, $skintemplate, $data );
	}

	/**
	 *
	 * @param \Skin $skin
	 * @param \SkinTemplate &$skintemplate
	 * @param array &$data
	 */
	public static function polulatePageInfosPanel( $skin, &$skintemplate, &$data ) {
		$data[SkinData::PAGE_INFOS_PANEL] +=
			self::addSectionContentNavigation( $skin, $skintemplate, $data, 'toolbox' );

		foreach ( $data[SkinData::PAGE_INFOS] as $value ) {
			$data[SkinData::PAGE_INFOS_PANEL][] = $value;
		}
	}

	/**
	 *
	 * @param \Skin $skin
	 * @param \SkinTemplate &$skintemplate
	 * @param array &$data
	 */
	public static function polulatePageDocumentsPanel( $skin, &$skintemplate, &$data ) {
		foreach ( $data[SkinData::PAGE_DOCUMENTS] as $value ) {
			$data[SkinData::PAGE_DOCUMENTS_PANEL][] = $value;
		}
	}

	/**
	 *
	 * @param \Skin $skin
	 * @param \SkinTemplate &$skintemplate
	 * @param array &$data
	 */
	public static function polulatePageSettingsPanel( $skin, &$skintemplate, &$data ) {
		$data[SkinData::PAGE_TOOLS] +=
			self::addSectionContentNavigation( $skin, $skintemplate, $data, 'views' );
		$data[SkinData::PAGE_TOOLS] +=
			self::addSectionContentNavigation( $skin, $skintemplate, $data, 'pageactions' );
		foreach ( $data[SkinData::PAGE_TOOLS] as $value ) {
			$data[SkinData::PAGE_TOOLS_PANEL][] = $value;
		}
	}

	/**
	 *
	 * @param \Skin $skin
	 * @param \SkinTemplate &$skintemplate
	 * @param array &$data
	 * @param string $group
	 * @return array
	 */
	public static function addSectionContentNavigation( $skin, &$skintemplate, &$data, $group ) {
		$content_navigation_data = $data[SDFD::CONTENT_NAVIGATION_DATA];
		$sectionMsgKey = "bs-sitetools-" . $group;
		$sectionMsg = wfMessage( $sectionMsgKey )->plain();
		$groupMsgKey = "bs-sitetools-content-navigation-group-" . $group;
		$groupMsg = wfMessage( $groupMsgKey )->plain();

		$list = [];
		foreach ( $content_navigation_data as $key => $value ) {
			if ( $value['bs-group'] !== $group ) { continue;
			}
			$list[$sectionMsgKey][] = $value;
		}

		if ( empty( $list ) ) { return [];
		}

		return [
			$sectionMsgKey => [
				'position' => 20,
				'label' => wfMessage( $sectionMsgKey )->plain(),
				'type' => 'linklist',
				'content' => $list[$sectionMsgKey]
			]
		];
	}

	/**
	 *
	 * @param string $href
	 * @param \Title $title
	 * @param string $text
	 * @param string $label
	 * @param int $position
	 * @param string $data
	 * @return string
	 */
	public static function addDefaultLinkItem( $href, $title, $text, $label, $position, $data = '' ) {
		$icon = \Html::element( 'i', [ 'style' => 'padding-left:3em;' ], '' );

		$html = \Html::rawElement(
			'a',
			[
				'href' => $href,
				'title' => $title,
			],
			$icon . $text
		);

		return [
			'position' => $position,
			'label' => $label,
			'type' => 'html',
			'content' => $html
		];
	}

	/**
	 *
	 * @param int $section
	 * @param string $label
	 * @param int $position
	 * @param string $html
	 * @param string $attr
	 * @param string $classes
	 * @return array
	 */
	public static function addSection( $section, $label, $position, $html,
			$attr = '', $classes = '' ) {
		return [
				$section => [
					'position' => $position,
					'label' => $label,
					'type' => 'html',
					'content' =>
						'<div class="' . $classes . ' "' . $attr . '>'
						. $html
						. '</div>'
				]
			];
	}
}
