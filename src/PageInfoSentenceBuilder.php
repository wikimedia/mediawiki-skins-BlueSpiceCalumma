<?php

namespace BlueSpice\Calumma;

use Title;
use User;
use BlueSpice\PageInfoElement;
use BlueSpice\IPageInfoElement;
use Exception;

class PageInfoSentenceBuilder {

	/**
	 *
	 * @var IContextSource|null
	 */
	private $context = null;

	/**
	 *
	 * @var PageInfoElement[]|null
	 */
	private $elements = null;

	/**
	 *
	 * @var Title|null
	 */
	private $title = null;

	/**
	 *
	 * @var User|null
	 */
	private $user = null;

	/**
	 *
	 * @param IContextSource|null $context
	 */
	public function __construct( \IContextSource $context = null ) {
		$this->context = $context;
	}

	/**
	 * @param IPageInfoElement[] $elements
	 * @param Title $title
	 * @param User $user
	 * @return string
	 */
	public function build( $elements, Title $title, User $user ) {
		$items = [];
		foreach ( $elements as $name => $element ) {

			if ( $element instanceof IPageInfoElement === false ) {
				throw new Exception( "Not a PageInfoElement: '$name'" );
			}

			if ( !$element->shouldShow( $this->context ) ) {
				continue;
			}

			$items[$name] = [
				'position' => $element->getPosition(),
				'item-class' => $element->getItemClass(),
				'type' => $element->getType(),
				'content' => $element->getLabelMessage()->plain(),
				'tooltip' => $element->getTooltipMessage()->plain(),
				'class' => $element->getHtmlClass(),
				'url' => $element->getUrl(),
				'menu' => $element->getMenu(),
				'name' => $element->getName()
			];
		}

		$pro = [];
		$contra = [];

		$this->sortPageInfoItems( $items, $pro, $contra );
		$sentence = $this->makePagInfoSentence( $pro, $contra );

		return $sentence;
	}

	/**
	 *
	 * @param array $items
	 * @param array &$pro
	 * @param array &$contra
	 */
	private function sortPageInfoItems( $items, &$pro, &$contra ) {
		foreach ( $items as $key => $value ) {
			if ( !array_key_exists( 'item-class', $value ) ) {
				continue;
			}
			if ( $value[ 'item-class' ] === 'pro' ) {
				$pro[$key] = $value;
			}
			if ( $value[ 'item-class' ] === 'contra' ) {
				$contra[$key] = $value;
			}
		}

		usort( $pro, function ( $a, $b ) {
			if ( !isset( $a['position'] ) ) {
				return false;
			}
			if ( !isset( $b['position'] ) ) {
				return true;
			}
			return $a['position'] > $b['position'];
		} );

		usort( $contra, function ( $a, $b ) {
			if ( !isset( $a['position'] ) ) {
				return false;
			}
			if ( !isset( $b['position'] ) ) {
				return true;
			}
			return $a['position'] > $b['position'];
		} );
	}

	/**
	 *
	 * @param string $itemclass
	 * @return array
	 */
	private function getSentenceParts( $itemclass ) {
		$parts = [];

		foreach ( $itemclass as $key => $value ) {
			if ( !array_key_exists( 'content', $value ) ) {
				continue;
			}
			$type = $value[ 'type' ];

			switch ( $type ) {
				case 'text':
					$parts[] = \Html::element(
							'span',
							[
								'class' => $value[ 'class' ],
								'title' => $value[ 'tooltip' ]
							],
							$value[ 'content' ]
						);
					break;
				case 'link':
					$parts[] = \Html::element(
							'a',
							[
								'class' => $value[ 'class' ],
								'href' => $value[ 'url' ],
								'title' => $value[ 'tooltip' ]
							],
							$value[ 'content' ]
						);
					break;
				case 'menu':
					$parts[] = $this->makeDropdownMenu( $value );
					break;
			}

		}

		return $parts;
	}

	/**
	 *
	 * @param array $pro
	 * @param array $contra
	 * @return string
	 */
	private function makePagInfoSentence( $pro, $contra ) {
		$hasPro = ( count( $pro ) > 0 ) ? true : false;
		$hasContra = ( count( $contra ) > 0 ) ? true : false;

		if ( !$hasPro && !$hasContra ) {
			return '';
		}

		$sentence = '';

		if ( $hasContra && ( count( $contra ) > 2 ) ) {
			$lastItem = array_pop( $contra );
			$parts = $this->getSentenceParts( $contra );
			$separator = wfMessage(
				'bs-calumma-pageinfosentence-item-separator-greater-two'
			)->plain();
			$separator .= ' ';
			$sentence .= implode( $separator, $parts );

			$sentence .= ' ';
			$sentence .= wfMessage(
				'bs-calumma-pageinfosentence-item-separator'
			)->plain();
			$sentence .= ' ';
			$sentence .= $lastItem['content'];
		} elseif ( $hasContra && !( count( $contra ) > 2 ) ) {
			$parts = $this->getSentenceParts( $contra );
			$separator .= ' ';
			$separator = wfMessage(
				'bs-calumma-pageinfosentence-item-separator'
			)->plain();
			$separator .= ' ';
			$sentence .= implode( $separator, $parts );
		}

		if ( $hasContra && $hasPro ) {
			$sentence .= ' ';
			$sentence .= wfMessage(
				'bs-calumma-pageinfosentence-item-class-separator'
			)->plain();
			$sentence .= ' ';
		}

		if ( $hasPro && ( count( $pro ) > 2 ) ) {
			$lastItem = array_pop( $pro );
			$parts = $this->getSentenceParts( $pro );
			$separator = wfMessage(
				'bs-calumma-pageinfosentence-item-separator-greater-two'
			)->plain();
			$separator .= ' ';
			$sentence .= implode( $separator, $parts );

			$sentence .= ' ';
			$sentence .= wfMessage(
				'bs-calumma-pageinfosentence-item-separator'
			)->plain();
			$sentence .= ' ';
			$sentence .= $lastItem['content'];
		} elseif ( $hasPro && !( count( $pro ) > 2 ) ) {
			$parts = $this->getSentenceParts( $pro );
			$separator .= ' ';
			$separator = wfMessage(
				'bs-calumma-pageinfosentence-item-separator'
			)->plain();
			$separator .= ' ';
			$sentence .= implode( $separator, $parts );
		}

		return wfMessage( 'bs-calumma-pageinfosentence', $sentence )->plain();
	}

	/**
	 *
	 * @param array $value
	 * @return string
	 */
	private function makeDropdownMenu( $value ) {
		$html = '';

		$html .= \Html::openElement(
				'span',
				[
					'class' => 'dropdown'
				]
			);

		$html .= \Html::element(
				'a',
				[
					'class' => $value[ 'class' ],
					'id' => 'pageinfo-' . $value[ 'name' ]
				],
				$value[ 'content' ]
			);

		$html .= \Html::openElement(
				'a',
				[
					'class' => ' dropdown-toggle',
					'href' => $value[ 'url' ],
					'data-toggle' => 'dropdown',
					'aria-expanded' => 'false',
					'title' => $value[ 'tooltip' ]
				]
			);

		$html .= \Html::closeElement( 'a' );

		$html .= \Html::openElement(
				'div',
				[
					'class' => 'dropdown-menu'
				]
			);

		$html .= $value['menu'];

		$html .= \Html::closeElement( 'div' );

		$html .= \Html::closeElement( 'span' );

		return $html;
	}

}
