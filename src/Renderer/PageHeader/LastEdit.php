<?php

namespace BlueSpice\Calumma\Renderer\PageHeader;

use Exception;
use Html;
use Config;
use HtmlArmor;
use WikiPage;
use User;
use IContextSource;
use RequestContext;
use QuickTemplate;
use BlueSpice\Services;
use BlueSpice\UtilityFactory;
use BlueSpice\Renderer;
use BlueSpice\Renderer\Params;
use BlueSpice\Timestamp;
use MediaWiki\Linker\LinkRenderer;
use Revision;
use BlueSpice\Calumma\Renderer\PageHeader;

class LastEdit extends PageHeader {

	/**
	 *
	 * @var UtilityFactory
	 */
	protected $util = null;

	/**
	 * LastEdit constructor.
	 * @param Config $config
	 * @param Params $params
	 * @param LinkRenderer|null $linkRenderer
	 * @param IContextSource|null $context
	 * @param string $name
	 * @param QuickTemplate|null $skinTemplate
	 * @param UtilityFactory|null $util
	 */
	protected function __construct( Config $config, Params $params,
		LinkRenderer $linkRenderer = null, IContextSource $context = null,
		$name = '', QuickTemplate $skinTemplate = null, UtilityFactory $util = null ) {
		parent::__construct( $config, $params, $linkRenderer, $context, $name, $skinTemplate );

		$this->util = $util;
	}

	/**
	 *
	 * @param string $name
	 * @param Services $services
	 * @param Config $config
	 * @param Params $params
	 * @param IContextSource|null $context
	 * @param LinkRenderer|null $linkRenderer
	 * @param QuickTemplate|null $skinTemplate
	 * @param UtilityFactory|null $util
	 * @return Renderer
	 */
	public static function factory( $name, Services $services, Config $config, Params $params,
		IContextSource $context = null, LinkRenderer $linkRenderer = null,
		QuickTemplate $skinTemplate = null, UtilityFactory $util = null ) {
		if ( !$context ) {
			$context = $params->get(
				static::PARAM_CONTEXT,
				false
			);
			if ( !$context instanceof IContextSource ) {
				$context = RequestContext::getMain();
			}
		}
		if ( !$linkRenderer ) {
			$linkRenderer = $services->getLinkRenderer();
		}
		if ( !$util ) {
			$util = $services->getService( 'BSUtilityFactory' );
		}
		if ( !$skinTemplate ) {
			$skinTemplate = $params->get( static::SKIN_TEMPLATE, null );
		}
		if ( !$skinTemplate ) {
			throw new Exception(
				'Param "' . static::SKIN_TEMPLATE . '" must be an instance of '
				. QuickTemplate::class
			);
		}

		return new static(
			$config,
			$params,
			$linkRenderer,
			$context,
			$name,
			$skinTemplate,
			$util
		);
	}

	/**
	 * @return string
	 */
	public function render() {
		$html = '';
		$title = $this->getContext()->getTitle();
		if ( !$title || $title->isSpecialPage() ) {
			return $html;
		}

		$wikiPage = WikiPage::factory( $title );
		$currentRevision = $this->getCurrentRevision( $wikiPage );

		if ( !$currentRevision ) {
			return '';
		}

		$diffLink = $this->makeLastEditDiffLink( $wikiPage, $currentRevision );
		$userLink = $this->makeLastEditorLink( $wikiPage, $currentRevision );

		$lastEditText = $this->msg(
			'bs-calumma-page-last-edit-text',
			$diffLink,
			$userLink
		)->text();

		return Html::rawElement(
			'span',
			[ 'class' => "bs-page-last-edit" ],
			$lastEditText
		);
	}

	/**
	 *
	 * @param WikiPage $wikiPage
	 * @param Revision $currentRevision
	 * @return string
	 */
	protected function makeLastEditDiffLink( WikiPage $wikiPage, $currentRevision ) {
		$rawTimestamp = $currentRevision->getTimestamp();
		$formattedDate = $this->getContext()->getLanguage()->date( $rawTimestamp );

		$revisionTimestamp = Timestamp::getInstance( $rawTimestamp );
		$formattedPeriod = $revisionTimestamp->getAgeString( null, null, 1 );

		$diffLink = $this->linkRenderer->makeLink(
			$wikiPage->getTitle(),
			new HtmlArmor( $formattedPeriod ),
			[
				'title' => $formattedDate,
				'rel' => 'nofollow',
			],
			[
				'oldid' => $currentRevision->getId(),
				'diff' => 'prev'
			]
		);

		return $diffLink;
	}

	/**
	 *
	 * @param WikiPage $wikiPage
	 * @param Revision $currentRevision
	 * @return string
	 */
	protected function makeLastEditorLink( WikiPage $wikiPage, $currentRevision ) {
		/* Main_page is created with user id 0 */
		if ( !$currentRevision->getUser() ) {
			return $this->msg( 'bs-calumma-page-last-edit-by-system-user' )->plain();
		}

		$user = User::newFromId( $currentRevision->getUser() );

		$userLink = $this->linkRenderer->makeLink(
			$user->getUserPage(),
			$this->util->getUserHelper( $user )->getDisplayName()
		);

		return $userLink;
	}

	/**
	 * @param WikiPage $wikiPage
	 * @return Revision|null
	 */
	protected function getCurrentRevision( WikiPage $wikiPage ) {
		return $wikiPage->getRevision();
	}
}
