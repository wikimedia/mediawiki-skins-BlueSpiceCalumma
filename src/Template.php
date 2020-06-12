<?php

namespace BlueSpice\Calumma;

/**
 * BaseTemplate class for the Chameleon skin
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 */
class Template extends \Skins\Chameleon\ChameleonTemplate {

	/**
	 *
	 * @var SkinDataFieldDefinition
	 */
	protected $skinDataFieldDefinition = null;

	/**
	 *
	 * @param \Config|null $config
	 */
	public function __construct( \Config $config = null ) {
		parent::__construct( $config );

		$this->skinDataFieldDefinition =
			new SkinDataFieldDefinition( $this, $this->data );
		$this->skinDataFieldDefinition->init();
	}

	/**
	 * Outputs the entire contents of the page
	 * @return null
	 */
	public function execute() {
		$this->skinDataFieldDefinition->populateDefaultData();
		return parent::execute();
	}

	/**
	 * This is a cooy of BaseTemplate:getFooterLinks which was set
	 * to private.
	 *
	 * We need this until the Skin:Chameleon component is updated.
	 *
	 * @param string|null $option
	 * @return array|mixed
	 */
	public function getFooterLinks( $option = null ) {
		$footerlinks = $this->get( 'footerlinks' );

		// Reduce footer links down to only those which are being used
		$validFooterLinks = [];
		foreach ( $footerlinks as $category => $links ) {
			$validFooterLinks[$category] = [];
			foreach ( $links as $link ) {
				if ( isset( $this->data[$link] ) && $this->data[$link] ) {
					$validFooterLinks[$category][] = $link;
				}
			}
			if ( count( $validFooterLinks[$category] ) <= 0 ) {
				unset( $validFooterLinks[$category] );
			}
		}

		if ( $option == 'flat' ) {
			// fold footerlinks into a single array using a bit of trickery
			$validFooterLinks = array_merge( ...array_values( $validFooterLinks ) );
		}

		return $validFooterLinks;
	}

	/**
	 * This is a cooy of BaseTemplate:getFooterIcons which was set
	 * to private.
	 *
	 * We need this until the Skin:Chameleon component is updated.
	 *
	 * @param string|null $option
	 * @deprecated 1.35 read footer icons from template data requested via
	 *     $this->get('footericons')
	 * @return array
	 */
	public function getFooterIcons( $option = null ) {
		// Generate additional footer icons
		$footericons = $this->get( 'footericons' );

		if ( $option == 'icononly' ) {
			// Unset any icons which don't have an image
			foreach ( $footericons as &$footerIconsBlock ) {
				foreach ( $footerIconsBlock as $footerIconKey => $footerIcon ) {
					if ( !is_string( $footerIcon ) && !isset( $footerIcon['src'] ) ) {
						unset( $footerIconsBlock[$footerIconKey] );
					}
				}
			}
			// Redo removal of any empty blocks
			foreach ( $footericons as $footerIconsKey => &$footerIconsBlock ) {
				if ( count( $footerIconsBlock ) <= 0 ) {
					unset( $footericons[$footerIconsKey] );
				}
			}
		} elseif ( $option == 'nocopyright' ) {
			unset( $footericons['copyright']['copyright'] );
			if ( count( $footericons['copyright'] ) <= 0 ) {
				unset( $footericons['copyright'] );
			}
		}

		return $footericons;
	}

}
