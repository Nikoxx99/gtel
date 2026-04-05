<?php
namespace SiliconElementor\Data\Base;

use Elementor\Data\Base\Controller as Controller_Base;

/**
 * The Silicon Elementor controller base class.
 */
abstract class Controller extends Controller_Base {

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();

		$this->namespace = 'silicon-elementor/v1';
	}
}
