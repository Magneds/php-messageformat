<?php  //  Copyright ⓒ 2017 Konfirm - All Rights Reserved

namespace Konfirm\MessageFormat;

/**
 *  Class MessageFormatter
 *
 *  @package Konfirm\MessageFormat
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
	protected $message;

	/**
	 *  The ICU formatted message using indices innstead of keywords
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

		$message = $this->init($pattern);


		parent::__construct($locale, $message);
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
	 *  @param  string  $pattern
	 */
	public function setPattern($pattern) {
		parent::setPattern($this->init($pattern));
	}

	/**
	 *  Get the pattern used by the formatter
	 *
	 *  @param   bool    $original
	 *  @return  string  pattern
	 */
	public function getPattern($original=false) {
		return $original ? $this->pattern : parent::getPattern();
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
		$this->message = preg_replace_callback('/\{(\w+)\s*(?=,|\})/', function($match) {
			return str_replace($match[1], $this->catalog->indexOf($match[1]), $match[0]);
		}, $pattern);

		return $this->message;
	}
}
