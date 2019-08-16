<?php

namespace BlueSpice\Calumma\Renderer\PageHeader;

use Html;
use Title;
use SpecialPageFactory;
use BlueSpice\Renderer;

class BreadCrumb extends Renderer {

	/**
	 * @return string
	 */
	public function render() {
		$html = '';
		$title = $this->getContext()->getTitle();
		if ( !$title ) {
			return $html;
		}
		$html .= $this->makePortalLink( $title );
		$html .= $this->makeTitleLinks( $title );
		$html .= $this->makeTitleSubpages( $title );

		return $html;
	}

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	private function makePortalLink( Title $title ) {
		$namespaceText = $title->getNsText();

		if ( empty( $namespaceText ) ) {
			$namespaceText = $this->msg( 'bs-calumma-breadcrumbs-nstab-main' )->plain();
		}

		$portalSpecialPage = SpecialPageFactory::getPage( 'Allpages' );
		$href = $portalSpecialPage->getPageTitle()->getLocalURL( [
			'namespace' => $title->getNamespace()
		] );

		if ( $title->isSpecialPage() ) {
			$portalSpecialPage = SpecialPageFactory::getPage( 'SpecialPages' );
			$href = $portalSpecialPage->getPageTitle()->getLocalURL();
		}

		$portalLink = Html::openElement(
				'a',
				[
					'title' => $portalSpecialPage->getDescription(),
					'href' => $href
				]
			);

		$portalLink .= Html::element(
				'span',
				[
					'class' => 'bs-breadcrumbs-namespace'
				],
				$namespaceText
			);
		$portalLink .= Html::closeElement( 'a' );

		return $portalLink;
	}

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	private function makeTitleLinks( Title $title ) {
		$titleParts = explode( '/', $title->getText() );
		$titleCurrent = array_pop( $titleParts );

		$titleLinks = [];

		$root = '';
		foreach ( $titleParts as $titlePart ) {
			$root .= $titlePart;

			$part = Title::newFromText( $root, $title->getNamespace() );
			$titleLinks[] = $this->linkRenderer->makeLink( $part, $titlePart );

			$root .= '/';
		}

		$html = '';

		if ( !empty( $titleLinks ) || !empty( $titleCurrent ) ) {
			$html .= Html::element(
				'span',
				[
					'class' => 'bs-breadcrumbs-namespace-separator'
				],
				' '
			);
		}

		if ( !empty( $titleLinks ) ) {
			$breadcrumbsSeparator = Html::element(
				'span',
				[
					'class' => 'bs-breadcrumbs-separator'
				]
			);

			$html .= implode(
					' ' . $breadcrumbsSeparator . ' ',
					$titleLinks
				);

			$html .= ' ' . $breadcrumbsSeparator . ' ';
		}

		if ( !empty( $titleLinks ) && !empty( $titleCurrent ) ) {
			$html .= Html::openElement(
					'span',
					[
						'id' => 'bs-breadcrumbs-pages',
						'class' => 'dropdown'
					]
				);
			$nodeTitle = Title::newFromText(
				implode( '/', $titleParts ),
				$title->getNamespace()
			);

			$node = '';
			$dropdown = false;
			if ( $nodeTitle->exists() ) {
				$node = $nodeTitle->getPrefixedText();
				$dropdown = true;
			}

			$html .= Html::element(
					'a',
					[
						'class' => ( $dropdown )
							? 'bs-breadcrumbs-current-title dropdown-toggle'
							: 'bs-breadcrumbs-current-title',
						'data-toggle' => ( $dropdown ) ? 'dropdown' : '',
						'data-node' => $node,
						'title' => $this->msg(
							'bs-calumma-breadcrumbs-pages-title',
							$titleCurrent
						)->parse()
					],
					$title->getSubpageText()
				);

			if ( $dropdown ) {
				$html .= Html::openElement(
							'div',
							[
								'class' => 'dropdown-menu'
							]
						);
				$html .= Html::element(
						'ul',
						[
							'id' => 'bs-breadcrumbs-pages-list',
						]
					);
				$html .= Html::closeElement( 'div' );
			}

			$html .= Html::closeElement( 'span' );
		}

		if ( empty( $titleLinks ) && !empty( $titleCurrent ) ) {
			$html .= Html::element(
				'span',
				[
					'class' => 'bs-breadcrumbs-current-title'
				],
				$titleCurrent
			);
		}

		return $html;
	}

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	private function makeTitleSubpages( Title $title ) {
		if ( $title->isSpecialPage() ) {
			return '';
		}

		$subpages = $title->getSubpages();

		if ( !$subpages || !$subpages->count() ) {
			return '';
		}

		$titleParts = explode( '/', $title->getText() );
		$titleCurrent = array_pop( $titleParts );

		/* Get the number of all subpages */
		$count = $subpages->count();
		/* Get the number of direct subpages */
		foreach ( $subpages as $subpage ) {
			if ( $subpage->hasSubpages() ) {
				$count -= $subpage->getSubpages()->count();
			}
		}

		$html = '';

		$html .= Html::element(
				'span',
				[
					'class' => 'bs-breadcrumbs-subpages-separator'
				],
				' '
			);

		$html .= Html::openElement(
				'span',
				[
					'id' => 'bs-breadcrumbs-subpages',
					'class' => 'dropdown'
				],
				' '
			);

		$html .= Html::element(
				'a',
				[
					'class' => 'dropdown-toggle',
					'data-toggle' => 'dropdown',
					'data-count-direct' => $count,
					'data-count-all' => $subpages->count(),
					'data-node' => $title->getPrefixedText(),
					'title' => $this->msg(
						'bs-calumma-breadcrumbs-subpages-title',
						$count,
						$titleCurrent
					)->parse()
				],
				$this->msg(
					'bs-calumma-breadcrumbs-subpages',
					$count,
					$titleCurrent
				)->parse()
			);

		$html .= Html::openElement(
				'div',
				[
					'class' => 'dropdown-menu'
				]
			);

		$html .= Html::element(
				'ul',
				[
					'id' => 'bs-breadcrumbs-subpages-list',
				]
			);

		$html .= Html::closeElement( 'div' );
		$html .= Html::closeElement( 'span' );

		return $html;
	}
}
