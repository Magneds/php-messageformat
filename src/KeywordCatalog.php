<?php  //  Copyright ⓒ 2016-2018 Magneds - All Rights Reserved

namespace Magneds\MessageFormat;

/**
 * Class KeywordCatalog
 *
 * @package Magneds\MessageFormat
 */
class KeywordCatalog
{
    /**
     *  The array in which keywords are collected
     *
     * @var string[]
     */
    protected $keywords;

    /**
     *  KeywordCatalog constructor.
     *
     *  @param  string  ...$keywords
     */
    public function __construct(string ...$keywords)
    {
        $this->init(...$keywords);
    }

    /**
     *  Obtain the index at which the provided keyword is stored, if the keyword is not yet available within the
     *  stored values it is added
     *
     *  @param   string  $keyword
     *  @return  int
     */
    public function indexOf(string $keyword): int
    {
        if (!in_array($keyword, $this->keywords)) {
            $index = count($this->keywords);
            $this->keywords[$index] = $keyword;
        }

        return array_search($keyword, $this->keywords);
    }

    /**
     *  Obtain the keyword at the given index, if this index resolves into NULL, return the alternative value
     *
     *  @param   int          $index
     *  @param   string|null  $otherwise
     *  @return  string
     */
    public function keywordAt(int $index, string $otherwise = ''): string
    {
        return $this->keywords[$index] ?? $otherwise;
    }

    /**
     *  Create an index-based array from the collected keywords using the provided hash-tables as the data source
     *
     *  @param   array  $provider
     *  @return  array
     */
    public function indexed(array $provider): array
    {
        $values = [];

        foreach ($this->keywords as $index => $key) {
            $values[$index] = $provider[$key] ?? $provider[$index] ?? sprintf('{%s}', $key);
        }

        return $values;
    }

    /**
     *  (Re)set the internal keywords buffer
     *
     *  @param string ...$keywords
     */
    public function init(string ...$keywords)
    {
        $this->keywords = $keywords;
    }
}
