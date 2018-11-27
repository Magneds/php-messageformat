<?php  //  Copyright ⓒ 2017-2018 Magneds - All Rights Reserved

use Magneds\MessageFormat\KeywordCatalog;
use PHPUnit\Framework\TestCase;


class KeywordCatalogTest extends TestCase {
	public function testIndexOf() {
		$catalog = new KeywordCatalog();

		$this->assertEquals(0, $catalog->indexOf('foo'));
		$this->assertEquals(1, $catalog->indexOf('bar'));
		$this->assertEquals(2, $catalog->indexOf('baz'));
		$this->assertEquals(1, $catalog->indexOf('bar'));
		$this->assertEquals(0, $catalog->indexOf('foo'));
	}

	public function testKeywordAt() {
		$catalog = new KeywordCatalog();

		$this->assertEquals('', $catalog->keywordAt(0));
		$this->assertEquals('', $catalog->keywordAt(1));
		$this->assertEquals('', $catalog->keywordAt(2));

		$this->assertEquals('otherwise', $catalog->keywordAt(0, 'otherwise'));
		$this->assertEquals('otherwise', $catalog->keywordAt(1, 'otherwise'));
		$this->assertEquals('otherwise', $catalog->keywordAt(2, 'otherwise'));

		$this->assertEquals('', $catalog->keywordAt(0));
		$this->assertEquals('', $catalog->keywordAt(1));
		$this->assertEquals('', $catalog->keywordAt(2));

		$this->assertEquals(0, $catalog->indexOf('foo'));
		$this->assertEquals(1, $catalog->indexOf('bar'));
		$this->assertEquals(2, $catalog->indexOf('baz'));

		$this->assertEquals('foo', $catalog->keywordAt(0));
		$this->assertEquals('bar', $catalog->keywordAt(1));
		$this->assertEquals('baz', $catalog->keywordAt(2));

		$this->assertEquals('foo', $catalog->keywordAt(0, 'otherwise'));
		$this->assertEquals('bar', $catalog->keywordAt(1, 'otherwise'));
		$this->assertEquals('baz', $catalog->keywordAt(2, 'otherwise'));

		$this->assertEquals('foo', $catalog->keywordAt(0));
		$this->assertEquals('bar', $catalog->keywordAt(1));
		$this->assertEquals('baz', $catalog->keywordAt(2));
	}

	public function testIndexed() {
		$catalog = new KeywordCatalog();

		$catalog->indexOf('foo');
		$catalog->indexOf('bar');
		$catalog->indexOf('baz');

		$this->assertEquals(['{foo}', '{bar}', '{baz}'], $catalog->indexed([]));
		$this->assertEquals(['my foo', '{bar}', '{baz}'], $catalog->indexed(['foo' => 'my foo']));
		$this->assertEquals(['my foo', 'my bar', '{baz}'], $catalog->indexed(['foo' => 'my foo', 'bar' => 'my bar']));
		$this->assertEquals(['my foo', 'my bar', '{baz}'], $catalog->indexed(['bar' => 'my bar', 'foo' => 'my foo', ]));
		$this->assertEquals(['my foo', 'my bar', 'my baz'], $catalog->indexed(['foo' => 'my foo', 'bar' => 'my bar', 'baz' => 'my baz']));
		$this->assertEquals(['my foo', 'my bar', 'my baz'], $catalog->indexed(['bar' => 'my bar', 'foo' => 'my foo', 'baz' => 'my baz']));
	}

	public function testInit() {
		$catalog = new KeywordCatalog('foo');

		$this->assertEquals(0, $catalog->indexOf('foo'));
		$this->assertEquals(1, $catalog->indexOf('bar'));
		$this->assertEquals(2, $catalog->indexOf('baz'));

		$catalog->init('bar');

		$this->assertEquals(0, $catalog->indexOf('bar'));
		$this->assertEquals(1, $catalog->indexOf('foo'));
		$this->assertEquals(2, $catalog->indexOf('baz'));

		$catalog->init('baz', 'qux', 'foo', 'xyzzy', 'bar');

		$this->assertEquals(0, $catalog->indexOf('baz'));
		$this->assertEquals(2, $catalog->indexOf('foo'));
		$this->assertEquals(4, $catalog->indexOf('bar'));
	}
}
