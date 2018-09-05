<?php
$wgHooks['SkinTemplateOutputPageBeforeExec'] = function ( &$skin, &$template ) {
	$link = [
	'new-file 2' => [
		'position' => '30',
		'id' => 'new-file 2',
		'text' => 'new-file 2',
		'title' => wfMessage( 'bs-action-new-file-title' )->plain(),
		'href' => \SpecialPage::getTitleFor( 'Upload' )->getLocalURL()
	]
	];

	$template->data[BlueSpice\SkinData::FEATURED_ACTIONS] += [ 'new' => $link ];

	$link = [
	'new-file 3' => [
		'position' => '30',
		'id' => 'new-file 3',
		'text' => 'new-file 3',
		'title' => wfMessage( 'bs-action-new-file-title' )->plain(),
		'href' => \SpecialPage::getTitleFor( 'Upload' )->getLocalURL()
	]
	];
	$template->data[BlueSpice\SkinData::FEATURED_ACTIONS] += [ 'edit' => $link ];
};
