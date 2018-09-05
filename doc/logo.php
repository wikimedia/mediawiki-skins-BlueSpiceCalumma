<?php
$wgHooks['SkinTemplateOutputPageBeforeExec'] = function ( &$skin, &$template ) {
	$template->data[ 'bs_wiki_logo' ] = [
		'desktop' => [
			'position' => 10,
			'src' => '$wgLogo',
			'href' => '$title->getFullURL()',
			'text' => '$title->getText()',
			'class' => 'jabadabadoo'
			]
		];
};
