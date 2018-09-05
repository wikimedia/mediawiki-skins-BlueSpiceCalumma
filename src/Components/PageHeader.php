<?php

namespace BlueSpice\Calumma\Components;

use BlueSpice\Calumma\TemplateComponent;

class PageHeader extends TemplateComponent {

	/**
	 *
	 * @return string
	 */
	protected function getTemplatePathName() {
		return 'Calumma.Components.PageHeader';
	}

	/**
	 *
	 * @return array
	 */
	protected function getTemplateArgs() {
		$args = parent::getTemplateArgs();
		$args += [
			'sitenotice' => $this->getSiteNotice(),
			'indicators' => $this->getIndicators(),
			'firstheading' => $this->getFirstHeading(),
			'lang' => $this->getPageLanguageCode(),
			'headerlinks' => $this->getHeaderLinks()
		];
		return $args;
	}

	/**
	 *
	 * @return string
	 */
	protected function getSiteNotice() {
		return $this->getSkinTemplate()->get( 'sitenotice' );
	}

	/**
	 *
	 * @return string
	 */
	protected function getIndicators() {
		return $this->getSkinTemplate()->get( 'indicators' );
	}

	/**
	 *
	 * @return string
	 */
	protected function getFirstHeading() {
		return $this->getSkinTemplate()->get( 'title' );
	}

	/**
	 *
	 * @return string
	 */
	protected function getHeaderLinks() {
		return $this->getSkinTemplate()->get( 'headerlinks' );
	}

	/**
	 *
	 * @return string
	 */
	protected function getPageLanguageCode() {
		return $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();
	}
}
