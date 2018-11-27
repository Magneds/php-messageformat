<?php  //  Copyright ⓒ 2017 Konfirm - All Rights Reserved

namespace Konfirm\MessageFormat;

use PHPUnit\Framework\TestCase;


class MessageFormatterTest extends TestCase {
	public function testFormatNL() {
		$keywordPattern = 'Mijn naam is {name}';
		$numberPattern = 'Mijn naam is {0}';
		$keywordFormat = new MessageFormatter('nl', $keywordPattern);
		$numberFormat = new \MessageFormatter('nl', $numberPattern);
		$keywordData = ['name' => 'John Doe'];
		$numberData = ['Jane Doe'];

		$this->assertEquals($keywordPattern, $keywordFormat->format([]));
		$this->assertEquals($numberPattern, $numberFormat->format([]));

		$this->assertEquals('Mijn naam is John Doe', $keywordFormat->format($keywordData));
		$this->assertEquals('Mijn naam is Jane Doe', $keywordFormat->format($numberData));
		$this->assertEquals('Mijn naam is Jane Doe', $numberFormat->format($numberData));

		$this->assertEquals($keywordFormat->getPattern(true), $numberFormat->getPattern());
		$this->assertEquals($keywordPattern, $keywordFormat->getPattern());
	}

	public function testFormatEN() {
		$keywordPattern = 'A {severity} complex {type, select, example {"egg sample"} other {formatter {type}}}';
		$numberPattern = 'A {0} complex {1, select, example {"egg sample"} other {formatter {1}}}';
		$keywordFormat = new MessageFormatter('en', $keywordPattern);
		$numberFormat = new \MessageFormatter('en', $numberPattern);
		$keywordData = ['severity' => 'slightly', 'type' => 'use case'];
		$numberData = ['much more', 'example'];

		$this->assertEquals('A {severity} complex formatter {type}', $keywordFormat->format([]));
		$this->assertEquals('A {0} complex {1}', $numberFormat->format([]));

		$this->assertEquals('A slightly complex formatter use case', $keywordFormat->format($keywordData));
		$this->assertEquals('A much more complex "egg sample"', $keywordFormat->format($numberData));
		$this->assertEquals('A much more complex "egg sample"', $numberFormat->format($numberData));

		$this->assertEquals($keywordFormat->getPattern(true), $numberFormat->getPattern());
		$this->assertEquals($keywordPattern, $keywordFormat->getPattern());
	}

	public function testSetPatternEN() {
		$patternOne = 'A {%s} walks into a {%s}';
		$patternTwo = 'One small step for {%s}, one giant leap for {%s}';

		$namedOne = sprintf($patternOne, 'foo', 'bar');
		$namedTwo = sprintf($patternTwo, 'individual', 'group');

		$indexedOne = sprintf($patternOne, '0', '1');
		$indexedTwo = sprintf($patternTwo, '0', '1');

		$format = new MessageFormatter('en', $namedOne);

		$this->assertEquals($indexedOne, $format->getPattern(true));
		$this->assertEquals($namedOne, $format->getPattern());

		$format->setPattern($namedTwo);

		$this->assertEquals($indexedTwo, $format->getPattern(true));
		$this->assertEquals($namedTwo, $format->getPattern());
	}
}
