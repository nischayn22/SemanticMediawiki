<?php

namespace SMW\Test;
use SMWQueryProcessor;

/**
 * Tests for the SMWQueryProcessor class.
 *
 * @file
 * @since 1.8
 *
 * @ingroup SMW
 * @ingroup Test
 *
 * @group SMW
 * @group SMWExtension
 * @group SMWQueries
 *
 * @author Nischay Nahata
 */
class SMWQueryProcessorTest extends\ MediaWikiTestCase {

	public function createQueryDataProvider() {
		return array(
			array( '[[Modification date::+]]|?Modification date|sort=Modification date|order=desc' ),
		);
	}

	/**
	* @dataProvider createQueryDataProvider
	*/
	public function testCreateQuery( $query ) {
		// TODO: this prevents doing [[Category:Foo||bar||baz]], must document.
		$rawParams = explode( '|', $query );
		$queryString = '';
		$printouts = array();

		SMWQueryProcessor::processFunctionParams( $rawParams, $queryString, $parameters, $printouts );
		SMWQueryProcessor::addThisPrintout( $printouts, $parameters );
		$parameters = SMWQueryProcessor::getProcessedParams( $parameters, $printouts );
		
		$this->assertInstanceOf(
			'\SMWQuery',
			SMWQueryProcessor::createQuery(
				$queryString,
				$parameters,
				SMWQueryProcessor::SPECIAL_PAGE,
				'',
				$printouts
			),
			"Result should be instance of SMWQuery."
		);
	}

}