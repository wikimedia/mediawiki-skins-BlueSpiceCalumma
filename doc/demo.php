<?php

$wgHooks['SkinTemplateOutputPageBeforeExec'] = function ( &$skin, &$template ) {
	// vorher skindata
	$authors = [
			0 => [
				'name' => 'element_1',
				'href' => '',
				'title' => 'element_1',
				'text' => 'element_1',
				],
			1 => [
				'name' => 'element_2',
				'href' => '',
				'title' => 'element_2',
				'text' => 'element_2',
				],
		];
		$template->data[BlueSpice\SkinData::PAGE_INFOS]['Infos'] = $authors;

		$template->data[BlueSpice\SkinData::PAGE_SETTINGS]['Settings'] = [
			'element_1' => [
				'id' => '#page_settingspanel_1_1',
				'href' => '#',
				'title' => 'element_1_title',
				'text' => 'element_1_text',
				'classes' => ''
			],
			'element_2' => [
				'id' => '#page_settingspanel_1_2',
				'href' => '#',
				'title' => 'element_2_title',
				'text' => 'element_2_text',
				'classes' => ''
			]
		];
		$template->data[BlueSpice\SkinData::PAGE_DOCUMENTS]['Documents'] = [
			'element_1' => [
				'id' => '#page_documentspanel_1_1',
				'href' => '#',
				'title' => 'element_1_title',
				'text' => 'element_1_text',
				'classes' => ''
			],
			'element_2' => [
				'id' => '#page_documentspanel_1_2',
				'href' => '#',
				'title' => 'element_2_title',
				'text' => 'element_2_text',
				'classes' => ''
			]
		];
		$template->data[BlueSpice\SkinData::PAGE_TOOLS]['Tools'] = [
			'element_1' => [
				'id' => '#page_toolspanel_1_1',
				'href' => '#',
				'title' => 'element_1_title',
				'text' => 'element_1_text',
				'classes' => ''
			],
			'element_2' => [
				'id' => '#page_toolspanel_1_2',
				'href' => '#',
				'title' => 'element_2_title',
				'text' => 'element_2_text',
				'classes' => ' icon-print',
				'data' => [
					0 => [
						'key' => 'test',
						'value' => 'testval'
					]
				]
			]
		];
// localsettings

	$template->data[BlueSpice\SkinData::PAGE_SETTINGS]['Additonal'] = [
			'element_1' => [
				'id' => '#page_settingspanel_2_1',
				'href' => '#',
				'title' => 'element_1_title',
				'text' => 'element_1_text',
				'classes' => ''
			],
			'element_2' => [
				'id' => '#page_settingspanel_2_2',
				'href' => '#',
				'title' => 'element_2_title',
				'text' => 'element_2_text',
				'classes' => ''
			],
			'element_3' => [
				'id' => '#page_settingspanel_2_3',
				'href' => '#',
				'title' => 'element_3_title',
				'text' => 'element_3_text',
				'classes' => ''
			]
		];
};
