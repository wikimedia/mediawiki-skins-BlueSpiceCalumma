<?php


namespace BlueSpice\Calumma\Components;

class FullScreenButton extends \Skins\Chameleon\Components\Structure {
	/**
	 * The resulting HTML
	 * @return string
	 */
	public function getHtml() {
		$data = 'bs-full-screen-mode';
		$class = $this->getDomElement()->getAttribute( 'class' );

		$html = \Html::openElement( 'a', [
			'href' => '#',
			'title' => wfMessage( 'bs-calumma-full-screen-button-tooltip' )->plain(),
			'class' => ' calumma-full-screen-button ' . $class,
			'data-toggle' => $data,
			'role' => 'button'
		] );

		$html .= \Html::openElement( 'i', [ 'class' => $data ] );
		$html .= \Html::closeElement( 'i' );

		$html .= \Html::closeElement( 'a' );

		return $html;
	}
}
