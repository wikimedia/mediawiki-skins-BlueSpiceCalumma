<?php

namespace BlueSpice\Calumma\ConfigDefinition;

use BlueSpice\ConfigDefinition\BooleanSetting;

class BlueSpiceCalummaCustomMenuHeaderCollapse extends BooleanSetting {

	/**
	 *
	 * @return string[]
	 */
	public function getPaths() {
		return [
			static::MAIN_PATH_FEATURE . '/' . static::FEATURE_SKINNING . '/BlueSpiceCalumma',
			static::MAIN_PATH_PACKAGE . '/' . static::PACKAGE_FREE . '/BlueSpiceCalumma',
		];
	}

	/**
	 *
	 * @return string
	 */
	public function getLabelMessageKey() {
		return 'bs-calumma-pref-collapse-custommenu-header';
	}

	/**
	 *
	 * @return string
	 */
	public function getHelpMessageKey() {
		return 'bs-calumma-pref-collapse-custommenu-header-help';
	}
}
