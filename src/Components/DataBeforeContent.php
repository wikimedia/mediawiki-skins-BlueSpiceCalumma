<?php
namespace BlueSpice\Calumma\Components;

use BlueSpice\Calumma\Renderer\BreadCrumbRenderer;

class DataBeforeContent extends \Skins\Chameleon\Components\Structure {

	/**
	 * The resulting HTML
	 * @return string
	 */
	public function getHtml() {
		$html = \Html::openElement( 'div', [ 'class' => 'bs-data-before-content' ] );
		$html .= parent::getHtml();

		$dataBeforeContent = $this->getSkinTemplate()->get(
			\BlueSpice\SkinData::BEFORE_CONTENT
		);

		$title = $this->getSkin()->getTitle();

		if ( $title->isSpecialPage() || empty( $dataBeforeContent ) ) {
			$html .= BreadCrumbRenderer::doRender( $title, $this->getSkinTemplate() );
			$html .= \Html::closeElement( 'div' );
			return $html;
		}

		foreach ( $dataBeforeContent as $id => $data ) {
			$html .= \Html::openElement( 'div', [ 'class' => 'bs-data-before-content-' . $id ] );
			$html .= $data;
			$html .= \Html::closeElement( 'div' );
		}

		$html .= BreadCrumbRenderer::doRender( $title, $this->getSkinTemplate() );

		$html .= \Html::closeElement( 'div' );

		return $html;
	}
}
