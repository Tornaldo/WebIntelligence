<?php

namespace SMW\Tests\SPARQLStore;

use SMW\SPARQLStore\TurtleTriplesBuilder;
use SMW\SemanticData;
use SMW\DIWikiPage;

use SMWExpNsResource as ExpNsResource;
use SMWExporter as Exporter;

/**
 * @covers \SMW\SPARQLStore\TurtleTriplesBuilder
 *
 *
 * @group SMW
 * @group SMWExtension
 *
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class TurtleTriplesBuilderTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$semanticData = $this->getMockBuilder( '\SMW\SemanticData' )
			->disableOriginalConstructor()
			->getMock();

		$redirectLookup = $this->getMockBuilder( '\SMW\SPARQLStore\RedirectLookup' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceOf(
			'\SMW\SPARQLStore\TurtleTriplesBuilder',
			new TurtleTriplesBuilder( $semanticData, $redirectLookup )
		);
	}

	public function testBuildTriplesForEmptySemanticDataContainer() {

		$expNsResource = new ExpNsResource(
			'Redirect',
			Exporter::getNamespaceUri( 'wiki' ),
			'Redirect'
		);

		$semanticData = new SemanticData( new DIWikiPage( 'Foo', NS_MAIN, '' ) );

		$redirectLookup = $this->getMockBuilder( '\SMW\SPARQLStore\RedirectLookup' )
			->disableOriginalConstructor()
			->getMock();

		$redirectLookup->expects( $this->atLeastOnce() )
			->method( 'findRedirectTargetResource' )
			->will( $this->returnValue( $expNsResource ) );

		$instance = new TurtleTriplesBuilder( $semanticData, $redirectLookup );

		$this->assertTrue( $instance->doBuild()->hasTriplesForUpdate() );

		$this->assertInternalType( 'string', $instance->getTriples() );
		$this->assertInternalType( 'array', $instance->getPrefixes() );
	}

}
