<?php

namespace BlueSpice\Calumma\Renderer\PageHeader;

use Html;
use HtmlArmor;
use Title;
use Config;
use IContextSource;
use MediaWiki\Linker\LinkRenderer;
use BlueSpice\Renderer;
use BlueSpice\Renderer\Params;

class Category extends Renderer {
	const PARAM_CATEGORY_NAMES = 'categories';

	/**
	 * Constructor
	 * @param Config $config
	 * @param Params $params
	 * @param LinkRenderer|null $linkRenderer
	 * @param IContextSource|null $context
	 * @param string $name | ''
	 */
	protected function __construct( Config $config, Params $params,
		LinkRenderer $linkRenderer = null, IContextSource $context = null,
		$name = '' ) {
		parent::__construct( $config, $params, $linkRenderer, $context, $name );

		$this->args[static::PARAM_CATEGORY_NAMES] = $params->get(
			static::PARAM_CATEGORY_NAMES,
			[]
		);
	}

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

		$html .= $this->makeAddCategoryLink( $title );
		$html .= $this->makeCategoryLinks( $title );

		return $html;
	}

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	private function makeAddCategoryLink( Title $title ) {
		$html = '';

		$html .= Html::openElement(
				'div',
				[
					'class' => 'bs-category-container-add-category'
				]
			);

		$html .= Html::openElement(
				'a',
				[
					'id' => 'ca-insert_category',
					'class' => 'bs-category-add-category',
					'href' => '#',
					'title' => $this->msg( 'bs-calumma-category-add-category-title' )->plain()
				]
			);

		$html .= Html::element( 'i' );

		$html .= Html::element(
				'span',
				[],
				$this->msg( 'bs-calumma-category-add-category-text' )->plain()
			);

		$html .= Html::closeElement( 'a' );
		$html .= Html::closeElement( 'div' );

		return $html;
	}

	/**
	 *
	 * @param Title $title
	 * @return string
	 */
	private function makeCategoryLinks( Title $title ) {
		$html = '';

		$categoryLinks = [];
		foreach ( $this->args[static::PARAM_CATEGORY_NAMES] as $categoryName ) {
			$title = Title::makeTitle( NS_CATEGORY, $categoryName );
			if ( !$title ) {
				continue;
			}
			$categoryLinks[] = $this->linkRenderer->makeLink(
				$title,
				new HtmlArmor( $title->getText() ),
				[ 'class' => 'pill' ]
			);
		}

		$html .= Html::openElement(
				'div',
				[
					'class' => 'bs-category-container-categories'
				]
			);

		$html .= implode( '', $categoryLinks );

		$html .= Html::closeElement( 'div' );

		return $html;
	}
}
