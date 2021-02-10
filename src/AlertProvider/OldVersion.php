<?php

namespace BlueSpice\Calumma\AlertProvider;

use BlueSpice\AlertProviderBase;
use BlueSpice\Calumma\Controls\VersionPager;
use BlueSpice\IAlertProvider;
use MediaWiki\MediaWikiServices;
use Message;

class OldVersion extends AlertProviderBase {

	/**
	 *
	 * @var int
	 */
	protected $oldRevisionRecord = null;

	/**
	 *
	 * @return string
	 */
	public function getHTML() {
		$this->initOldId();

		if ( $this->isOldVersion() ) {
			$message = Message::newFromKey( 'bs-calumma-alert-oldpage' );
			$message->params( $this->skin->getTitle()->getArticleID() );

			$versionPagerHTML = $this->buildVersionPager();

			return $message->parse() . $versionPagerHTML;
		}

		return '';
	}

	/**
	 *
	 * @return string
	 */
	public function getType() {
		return IAlertProvider::TYPE_INFO;
	}

	/**
	 *
	 * @return bool
	 */
	private function isOldVersion() {
		if ( $this->oldRevisionRecord === null ) {
			return false;
		}
		$currentRevId = $this->skin->getTitle()->getLatestRevID();
		if ( $this->oldRevisionRecord->getId() === $currentRevId ) {
			return false;
		}

		return true;
	}

	/**
	 * @return string
	 */
	protected function buildVersionPager() {
		$services = MediaWikiServices::getInstance();
		$revisionLookup = $services->getRevisionLookup();
		$userLang = $this->skin->getLanguage();
		$title = $this->skin->getTitle();

		$oldId = $this->oldRevisionRecord ? $this->oldRevisionRecord->getId() : -1;
		$versionPager = new VersionPager( $revisionLookup, $userLang, $title, $oldId );

		return $versionPager->getHtml();
	}

	/**
	 * Resolves the currently displayed "oldRevisionRecord" from the "old revision view" URL parameters
	 */
	private function initOldId() {
		$oldId = $this->skin->getRequest()->getInt( 'oldid', -1 );
		$revisionLookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$this->oldRevisionRecord = $revisionLookup->getRevisionById( $oldId );
		if ( $this->oldRevisionRecord !== null ) {
			$direction = $this->skin->getRequest()->getVal( 'direction', '' );
			if ( $direction === 'next' ) {
				$this->oldRevisionRecord = $revisionLookup->getNextRevision( $this->oldRevisionRecord );
			}
			if ( $direction === 'prev' ) {
				$this->oldRevisionRecord = $revisionLookup->getPreviousRevision( $this->oldRevisionRecord );
			}
		}
	}
}
