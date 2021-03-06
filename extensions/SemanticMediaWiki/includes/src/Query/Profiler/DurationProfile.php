<?php

namespace SMW\Query\Profiler;

use SMW\DIProperty;
use SMWDINumber as DINumber;

/**
 * @license GNU GPL v2+
 * @since 1.9
 *
 * @author mwjames
 */
class DurationProfile extends ProfileAnnotatorDecorator {

	/**
	 * @var integer
	 */
	private $duration;

	/**
	 * @since 1.9
	 *
	 * @param ProfileAnnotator $profileAnnotator
	 * @param integer $duration
	 */
	public function __construct( ProfileAnnotator $profileAnnotator, $duration ) {
		parent::__construct( $profileAnnotator );
		$this->duration = $duration;
	}

	/**
	 * ProfileAnnotatorDecorator::addPropertyValues
	 */
	protected function addPropertyValues() {
		if ( $this->duration > 0 ) {
			$this->addGreaterThanZeroQueryDuration( $this->duration );
		}
	}

	private function addGreaterThanZeroQueryDuration( $duration ) {
		$this->getSemanticData()->addPropertyObjectValue(
			new DIProperty( '_ASKDU' ),
			new DINumber( $duration )
		);
	}

}