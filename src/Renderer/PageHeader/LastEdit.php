<?php

namespace BlueSpice\Calumma\Renderer\PageHeader;

use Html;
use HtmlArmor;
use WikiPage;
use User;
use MediaWiki\Revision\Revision;
use BlueSpice\Renderer;

class LastEdit extends Renderer {

	/**
	 *
	 * @return string
	 */
	public function render() {
		$html = '';
		$title = $this->getContext()->getTitle();
		if ( !$title || $title->isSpecialPage() ) {
			return $html;
		}

		$wikiPage = WikiPage::factory( $title );
		$currentRevision = $wikiPage->getRevision();

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
	private function makeLastEditDiffLink( WikiPage $wikiPage, $currentRevision ) {
		$rawTimestamp = $currentRevision->getTimestamp();
		$formattedDate = $this->getContext()->getLanguage()->date( $rawTimestamp );

		$unixTS = wfTimestamp( TS_UNIX, $rawTimestamp );
		$period = time() - $unixTS;

		$chosenIntervals = [];
		/* interval: 'years', 'days', 'hours', 'minutes' */

		/* more than one year */
		if ( ( $period > ( 365 * 24 * 60 * 60 ) ) ) {
			$chosenIntervals = [ 'years' ];
		}

		/* between one day and one year */
		if ( ( $period > ( 24 * 60 * 60 ) ) && ( $period < ( 365 * 24 * 60 * 60 ) ) ) {
			$chosenIntervals = [ 'days' ];
		}

		/* between one day and one hour */
		if ( ( $period > ( 60 * 60 ) ) && ( $period < ( 24 * 60 * 60 ) ) ) {
			$chosenIntervals = [ 'hours' ];
		}

		/* less than one hour */
		if ( ( $period < ( 60 * 60 ) ) ) {
			$chosenIntervals = [ 'minutes' ];
		}

		if ( $period < 60 ) {
			$chosenIntervals[] = 'seconds';
		}

		$formattedPeriod = $this->getContext()->getLanguage()->formatDuration(
			$period,
			$chosenIntervals
		);

		$diffLink = $this->linkRenderer->makeLink(
			$wikiPage->getTitle(),
			new HtmlArmor( $formattedPeriod ),
			[
				'title' => $formattedDate
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
	private function makeLastEditorLink( WikiPage $wikiPage, $currentRevision ) {
		/* Main_page is created with user id 0 */
		if ( !$currentRevision->getUser() ) {
			return $this->msg( 'bs-calumma-page-last-edit-by-system-user' )->plain();
		}

		$user = User::newFromId( $currentRevision->getUser() );

		$userLink = $this->linkRenderer->makeLink(
			$user->getUserPage(),
			$user->getRealName()
		);

		return $userLink;
	}
}
