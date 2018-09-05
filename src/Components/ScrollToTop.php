<?php
namespace BlueSpice\Calumma\Components;

class ScrollToTop extends \Skins\Chameleon\Components\Structure {

	/**
	 * The resulting HTML
	 * @return string
	 */
	public function getHtml() {
		$class = $this->getDomElement()->getAttribute( 'class' );

		$html = \Html::openElement( 'a', [
				'href' => '#',
				'class' => ' bs-top ' . $class,
				'role' => 'button'
			] );

		$html .= \Html::closeElement( 'a' );

		return $html;
	}
}
