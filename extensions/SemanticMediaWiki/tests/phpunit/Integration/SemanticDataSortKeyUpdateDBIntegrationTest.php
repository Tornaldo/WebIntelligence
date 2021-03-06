<?php

namespace SMW\Tests\Integration;

use SMW\Tests\MwDBaseUnitTestCase;
use SMW\Tests\Utils\UtilityFactory;

use SMW\DIWikiPage;
use SMW\DIProperty;
use SMW\SemanticData;
use SMW\DataValueFactory;
use SMW\Subobject;
use SMW\SerializerFactory;

use SMWDIBlob as DIBlob;

use Title;

/**
 * @group SMW
 * @group SMWExtension
 *
 * @group semantic-mediawiki-integration
 * @group mediawiki-database
 *
 * @group medium
 *
 * @license GNU GPL v2+
 * @since 2.1
 *
 * @author mwjames
 */
class SemanticDataSortKeyUpdateDBIntegrationTest extends MwDBaseUnitTestCase {

	private $semanticDataFactory;

	protected function setUp() {
		parent::setUp();

		$this->semanticDataFactory = UtilityFactory::getInstance()->newSemanticDataFactory();
	}

	protected function tearDown() {
		parent::tearDown();
	}

	public function testSubjectSortKeySetter() {

		$semanticData = $this->semanticDataFactory
			->newEmptySemanticData( __METHOD__ );

		$subject = $semanticData->getSubject();
		$subject->setSortKey( 'a_b_c' );

		$this->getStore()->updateData( $semanticData );

		$semanticDataFromDB = $this->getStore()->getSemanticData( $subject );

		$this->assertEquals(
			'a b c',
			$semanticDataFromDB->getSubject()->getSortKey()
		);

		foreach ( $semanticDataFromDB->getPropertyValues( new DIProperty( '_SKEY' ) ) as $value ) {
			$this->assertEquals(
				'a b c',
				$value->getString()
			);
		}
	}

	public function testDefinedSortKeyTakesPrecedenceOverSubjectSortKey() {

		$semanticData = $this->semanticDataFactory
			->newEmptySemanticData( __METHOD__ );

		$subject = $semanticData->getSubject();
		$subject->setSortKey( '1_2_3' );

		$semanticData->addPropertyObjectValue(
			new DIProperty( '_SKEY' ),
			new DIBlob( 'x_y_z' )
		);

		$this->getStore()->updateData( $semanticData );
		$this->getStore()->clear();

		$semanticDataFromDB = $this->getStore()->getSemanticData( $subject );

		$this->assertEquals(
			'x y z',
			$semanticDataFromDB->getSubject()->getSortKey()
		);

		foreach ( $semanticDataFromDB->getPropertyValues( new DIProperty( '_SKEY' ) ) as $value ) {
			$this->assertEquals(
				'x y z',
				$value->getString()
			);
		}
	}

}
