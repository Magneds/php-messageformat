<?php  //  Copyright ⓒ 2017-2018 Magneds - All Rights Reserved

namespace Magneds\MessageFormat\Tests;

use \Magneds\MessageFormat\MessageFormatter;
use \PHPUnit\Framework\TestCase;

class ReadMeExamplesTest extends TestCase
{
    public function testUsageExample()
    {
        $formatter = new MessageFormatter(
            'en',
            'Found {count, plural, =0 {no result} =1 {one result} other {# results}}'
        );

        $this->assertEquals('Found no result', $formatter->format(['count' => 0]));
        $this->assertEquals('Found one result', $formatter->format(['count' => 1]));
        $this->assertEquals('Found 2 results', $formatter->format(['count' => 2]));
    }

    public function testDropInReplacement()
    {
        $formatter = new MessageFormatter(
            'en',
            'Found {0, plural, =0 {no result} =1 {one result} other {# results}}'
        );

        $this->assertEquals('Found no result', $formatter->format([0]));
        $this->assertEquals('Found one result', $formatter->format([1]));
        $this->assertEquals('Found 2 results', $formatter->format([2]));
    }

    public function testCreate()
    {
        $en = MessageFormatter::create('en', 'Want {pi, number} now');

        $this->assertEquals('Want 3.142 now', $en->format(['pi' => M_PI]));
    }

    public function testFormatMessage()
    {
        $this->assertEquals(
            'Hello universe',
            MessageFormatter::formatMessage('en', 'Hello {audience}', ['audience' => 'universe'])
        );
    }

    public function testFormat()
    {
        $es = new MessageFormatter(
            'es-ES',
            'Por el pequeño precio de {price, number, currency} puedes comprar apps.'
        );

        $this->assertEquals(
            'Por el pequeño precio de 0,99 € puedes comprar apps.',
            $es->format(['price' => 0.99])
        );
    }

    public function testGetLocale()
    {
        $enNZ = new MessageFormatter('en-NZ', 'Hello {audience}');
        $nlBE = new MessageFormatter('nl-BE', 'Hallo {audience}');

        $this->assertEquals('en_NZ', $enNZ->getLocale());
        $this->assertEquals('nl_BE', $nlBE->getLocale());
    }

    public function testGetPattern()
    {
        $en = new MessageFormatter(
            'en',
            'Welcome back {name}, you have '.
            '{count, plural, =0{no unread messages} one{one unread message} other{# unread messages}}'
        );

        $this->assertEquals(
            'Welcome back {name}, you have ' .
            '{count, plural, =0{no unread messages} one{one unread message} other{# unread messages}}',
            $en->getPattern()
        );
        $this->assertEquals(
            'Welcome back {0}, you have ' .
            '{1, plural, =0{no unread messages} one{one unread message} other{# unread messages}}',
            $en->getPattern(true)
        );
        $this->assertEquals($en->getPattern(false), $en->getPattern());
    }

    public function testSetPattern()
    {
        $en = new MessageFormatter('en', 'Initial {value}');

        $this->assertEquals('Initial {value}', $en->getPattern());
        $this->assertEquals('Initial {0}', $en->getPattern(true));

        $en->setPattern('Override {value, number, percentage}');

        $this->assertEquals('Override {value, number, percentage}', $en->getPattern());
        $this->assertEquals('Override {0, number, percentage}', $en->getPattern(true));
    }

    public function testParse()
    {
        $nl = new MessageFormatter('nl', 'De {animal} {action} de {result} van de {target}');
        $message = 'De kat krabt de krullen van de trap';

        $parsed = $nl->parse($message);

        $this->assertEquals(
            ['animal' => 'kat', 'action' => 'krabt', 'result' => 'krullen', 'target' => 'trap'],
            $parsed
        );
    }

    public function testParseMessage()
    {
        $result = MessageFormatter::parseMessage(
            'en_US',
            '{monkeys,number,integer} monkeys on {trees,number,integer} trees ' .
            'make {distribution,number} monkeys per tree',
            '4,560 monkeys on 123 trees make 37.073 monkeys per tree'
        );

        $this->assertEquals(['monkeys' => 4560, 'trees' => 123, 'distribution' => 37.073], $result);
    }
}
