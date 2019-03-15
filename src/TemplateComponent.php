<?php

namespace BlueSpice\Calumma;

use Skins\Chameleon\Components\Component;

abstract class TemplateComponent extends Component {

	/**
	 *
	 * @var \TemplateParser
	 */
	protected $templateParser = null;

	/**
	 *
	 * @var string
	 */
	protected $renderedTemplate = '';

	/**
	 * The resulting HTML
	 * @return string
	 */
	public function getHtml() {
		$this->initTemplateParser();
		$this->renderTemplate();
		return $this->renderedTemplate;
	}

	/**
	 *
	 * @var \TemplateParser[]
	 */
	protected static $templateParsers = [];

	/**
	 * Initializes the internal \TemplateParser object
	 */
	protected function initTemplateParser() {
		$templatePath = $this->getTemplatePath();
		if ( !isset( static::$templateParsers[$templatePath ] ) ) {
			static::$templateParsers[$templatePath ] = new \TemplateParser( $templatePath );
		}

		$this->templateParser = static::$templateParsers[$templatePath ];
	}

	/**
	 *
	 */
	protected function renderTemplate() {
		$this->renderedTemplate = $this->templateParser->processTemplate(
			$this->getTemplateName(),
			$this->getTemplateArgs()
		);
	}

	/**
	 * @return string
	 */
	abstract protected function getTemplatePathName();

	/**
	 *
	 * @return array
	 */
	protected function getTemplateArgs() {
		$domElement = $this->getDomElement();
		$attributes = [];

		if ( $domElement instanceof \DOMElement === false ) {
			return $attributes;
		}

		foreach ( $domElement->attributes as $domAttr ) {
			if ( $domAttr instanceof \DOMAttr === false ) {
				continue;
			}
			$attributes[$domAttr->name] = $domAttr->value;
		}

		return $attributes;
	}

	/**
	 *
	 * @return string
	 */
	protected function getTemplatePath() {
		$pathname = $this->getTemplatePathName();
		$parts = explode( '.', $pathname );
		array_pop( $parts );
		$subPath = implode( '/', $parts );

		return dirname( __DIR__ ) . "/resources/templates/$subPath";
	}

	/**
	 *
	 * @return string
	 */
	protected function getTemplateName() {
		$pathname = $this->getTemplatePathName();
		$parts = explode( '.', $pathname );
		return array_pop( $parts );
	}
}
