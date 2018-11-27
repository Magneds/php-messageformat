# magneds/messageformat
The PHP Intl [MessageFormatter](https://php.net/manual/en/class.messageformatter.php) class works great, except for the positional variables syntax. This library solves that by providing a variable name based solution, making it easier for translators to translate content with more of an understanding of what variables represent.

As a positive side effect, it also makes the code more robust by allowing for change in variables without compromising the variable order.

## Installation
```
$ composer require magneds/messageformat
```  

## Usage
```php
<?php

use Magneds\MessageFormat\MessageFormatter;

$formatter = new MessageFormatter('en', 'Found {count, plural, =0 {no result} =1 {one result} other {# results}}');

print $formatter->format(['count' => 0]);  //  Found no result
print $formatter->format(['count' => 1]);  //  Found one result
print $formatter->format(['count' => 2]);  //  Found 2 results
```

As the MessageFormatter is a drop-in replacement to the PHP Intl [MessageFormatter](https://php.net/manual/en/class.messageformatter.php), the position based variables also still work. This should make migration pretty straight-forward.
```php
<?php

use Magneds\MessageFormat\MessageFormatter;

$formatter = new MessageFormatter('en', 'Found {0, plural, =0 {no result} =1 {one result} other {# results}}');

print $formatter->format([0]);  //  Found no result
print $formatter->format([1]);  //  Found one result
print $formatter->format([2]);  //  Found 2 results

```

## API

### `MessageFormatter::__construct(string $locale , string $pattern)`
Create a new MessageFormatter instance rendering the provided pattern for the locale.

```php
<?php

use Magneds\MessageFormat\MessageFormatter;

$en = new MessageFormatter('en', 'Hello {audience}');
$es = new MessageFormatter('es', 'Hola {audience}');
$de = new MessageFormatter('de', 'Hallo {audience}');
```

### `static MessageFormatter::create(string $locale, string $pattern)`
Create a new instance of MessageFormatter

```php
<?php

use Magneds\MessageFormat\MessageFormatter;

$en = MessageFormatter::create('en', 'Hello {audience}');
$es = MessageFormatter::create('es', 'Hola {audience}');
$de = MessageFormatter::create('de', 'Hallo {audience}');
```

### `static MessageFormatter::formatMessage(string $locale, string $pattern, array $args)`
Quickly format a message, without explicitly creating a new instance of MessageFormatter.

```php
<?php

use Magneds\MessageFormat\MessageFormatter;

print MessageFormatter::formatMessage('en', 'Hello {audience}', ['audience' => 'universe']); //  Hello universe
```

### `string MessageFormatter::format (array $args)`
The format method is what actually does the rendering the pattern into a localized string.

```php
<?php

use Magneds\MessageFormat\MessageFormatter;

$es = new MessageFormatter('es-ES', 'Por el pequeño precio de {price, number, currency} puedes comprar apps.');

print $es->format(['price' => 0.99]); //  Por el pequeño precio de 0,99 € puedes comprar apps.
```

### `string MessageFormatter::getLocale(void)`
Obtain the locale from the MessageFormatter instance

```php
<?php

use Magneds\MessageFormat\MessageFormatter;

$enNZ = new MessageFormatter('en-NZ', 'Hello {audience}');
$nlBE = new MessageFormatter('nl-BE', 'Hallo {audience}');

print $enNZ->getLocale();  //  'en_NZ'
print $nlBE->getLocale();  //  'nl_BE'
```

### `string MessageFormatter::getPattern([]bool $compatible=false])`
Obtain the pattern of the MessageFormatter instance. Optionally providing the PHP Intl MessageFormat compatible variant   

```php
<?php

use Magneds\MessageFormat\MessageFormatter;

$en = new MessageFormatter('en', 'Welcome back {name}, you have {count, plural, =0{no unread messages} one{one unread message} other{# unread messages}}');

print $en->getPattern();      //  Welcome back {name}, you have {count, plural, =0{no unread messages} one{one unread message} other{# unread messages}}
print $en->getPattern(true);  //  Welcome back {0}, you have {1, plural, =0{no unread messages} one{one unread message} other{# unread messages}}
```

### `static array MessageFormatter::parseMessage(string $locale, string $pattern, string $source)`
Quick parse output string, extracting all the variables

```php
<?php

use Magneds\MessageFormat\MessageFormatter;

print MessageFormatter::parseMessage(
    'en_US', 
    '{monkeys,number,integer} monkeys on {trees,number,integer} trees make {distribution,number} monkeys per tree',
    '4,560 monkeys on 123 trees make 37.073 monkeys per tree'
);  //  ['monkeys' => 4560, 'trees' => 123, 'distribution' => 37.073],
```

### `array MessageFormatter::parse(string $value)`
Extract the variables from a formatted string

```php
<?php

use Magneds\MessageFormat\MessageFormatter;

$nl = new MessageFormatter('nl', 'De {animal} {action} de {result} van de {target}');
$message = 'De kat krabt de krullen van de trap';

print $nl->parse($message);  //  ['animal' => 'kat', 'action' => 'krabt', 'result' => 'krullen', 'target' => 'trap']
```

### `bool MessageFormatter::setPattern(string $pattern)`
Sets a new pattern without changing the locale

```php
<?php

use Magneds\MessageFormat\MessageFormatter;

$en = new MessageFormatter('en', 'Initial {value}');

print $en->getPattern();      //  Initial {value}
print $en->getPattern(true);  //  Initial {0}

$en->setPattern('Override {value, number, percentage}');

print $en->getPattern();      //  Override {value, number, percentage}
print $en->getPattern(true);  //  Override {0, number, percentage}
```

## Change log

Please see [Changelog](CHANGELOG.md)

## Testing
```bash
$ composer test
```

## Contributing

Please see [Contributing](CONTRIBUTING.md)


## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.