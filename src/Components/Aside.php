<?php
namespace BlueSpice\Calumma\Components;

class Aside extends \Skins\Chameleon\Components\Structure {

	/**
	 * The resulting HTML
	 * @return string
	 */
	public function getHtml() {
		$class = $this->getDomElement()->getAttribute( 'class' );
		$toggleBy = $this->getDomElement()->getAttribute( 'data-toggle-by' );

		$html = \Html::openElement( 'aside', [
			'class' => $class,
			'data-toggle-by' => $toggleBy
			] );
		$html .= parent::getHtml();
		$html .= \Html::closeElement( 'aside' );

		return $html;
	}
}
