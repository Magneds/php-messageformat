<?php  //  Copyright ⓒ 2017-2018 Magneds - All Rights Reserved

namespace Magneds\MessageFormat;

/**
 *  Class MessageFormatter
 *
 *  @package Magneds\MessageFormat
 */
class MessageFormatter extends \MessageFormatter {
	/**
	 *  Keyword catalog, keeping track of variable name indices
	 *
	 *  @var  KeywordCatalog
	 */
	protected $catalog;

	/**
     *  The ICU formatted message (raw input)
	 *
	 *  @var  string
	 */
	protected $pattern;

	/**
	 *  MessageFormatter constructor.
	 *
	 *  @param  string  $locale
	 *  @param  string  $message
	 */
	public function __construct(string $locale, string $pattern) {
		$this->catalog = new KeywordCatalog();

		parent::__construct($locale, $this->init($pattern));
	}

	/**
	 *  Format the MessageFormatter using either an index-based or hash-table array
	 *
	 *  @param   array   $args
	 *  @return  string
	 */
	public function format($provider) {
		return parent::format($this->catalog->indexed($provider));
	}

	/**
	 *  Set the pattern used by the formatter
	 *
	 *  @param   string  $pattern
     *  @return  bool
	 */
	public function setPattern($pattern) {
		return parent::setPattern($this->init($pattern));
	}

	/**
	 *  Get the pattern used by the formatter
	 *
	 *  @param   bool    $compatible
	 *  @return  string  pattern
	 */
	public function getPattern($compatible=false) {
		return $compatible ? parent::getPattern() : $this->pattern;
	}

    /**
     *  Parse input string according to pattern
     *
     *  @param   string $value
     *  @return  array | bool
     */
    public function parse($value) {
        $parsed = parent::parse($value);

        return $parsed ? array_combine(array_map(function($index) {
            return $this->catalog->keywordAt((int) $index);
        }, array_keys($parsed)), $parsed) : $parsed;
    }

    /**
     * Quick parse string according to pattern
     *
     * @param   string $locale
     * @param   string $pattern
     * @param   string $source
     * @return  array|bool
     */
    public static function parseMessage($locale, $pattern, $source) {
        $formatter = new static($locale, $pattern);

        return $formatter->parse($source);
    }

    /**
	 *  Initialize the MessageFormatter
	 *
	 *  @param   string  $pattern
	 *  @return  string
	 */
	protected function init(string $pattern): string {
		$this->catalog->init();

		$this->pattern = $pattern;

		return preg_replace_callback('/\{(\w+)\s*(?=,|\})/', function($match) {
			return str_replace($match[1], $this->catalog->indexOf($match[1]), $match[0]);
		}, $pattern);
	}
}
