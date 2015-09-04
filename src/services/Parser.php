<?php namespace Rolice\LangSync;

use Symfony\Component\Finder\Finder;

/**
 * Class Parser
 * @package Rolice\LangSync
 */
class Parser
{

    /**
     * @var array
     */
    protected $extraction = [];
    /**
     * @var LabelsCollection
     */
    protected $collection;

    /**
     * @param LabelsCollection $collection
     */
    public function __construct(LabelsCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Extract all localization labels from files
     */
    public function extract()
    {
        foreach ($this->getFiles() as $file) {
            /**
             * @TODO: make it work for such lines:
             * Lang::get('common.' . $_ENV['client_currency'] . '-sign')
             * Lang::get("common.{$currency}-sign")
             */
            preg_match_all('#(?:\@lang|Lang::get)\((?<quot>\'|")(?<match>.*?)\k{quot}.*?\)#ui',
                $file->getContents(), $buffer);

            if (1 >= count($buffer)) {
                continue;
            }

            array_shift($buffer);

            $nest = function ($key, &$extraction, $index = 0) use (&$nest, $file) {
                $parts = explode('.', $key);

                // Recursion terminator
                if (count($parts) < $index) {
                    return;
                }

                // Put the value on the last index
                if (count($parts) <= $index + 1) {
                    $extraction[$parts[$index]] = $file;
                    return;
                }

                // Create new array on intermediate levels
                if (!isset($extraction[$parts[$index]])) {
                    $extraction[$parts[$index]] = [];
                }

                // Recurse to next level
                $nest($key, $extraction[$parts[$index]], $index + 1);
            };

            foreach ($buffer['match'] as $match) {
                $nest($match, $this->extraction);
            }
        }
    }

    /**
     * Get all files which will be parsed
     *
     * @return Finder
     */
    private function getFiles()
    {
        $finder = new Finder();
        $finder = $finder->files()->in(app_path())->name('/\.php$/');

        return $finder;
    }

    /**
     * @return LabelsCollection
     */
    public function get()
    {
        return $this->collection->set($this->extraction);
    }
}