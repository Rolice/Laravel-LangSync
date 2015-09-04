<?php namespace Rolice\LangSync;

use File;

/**
 * Class Localization
 * @package Rolice\LangSync
 */
class Localization
{
    const Separator = '.';

    /**
     * @var
     */
    private $language;
    /**
     * @var
     */
    private $file;

    /**
     * @param $language
     * @param $file
     */
    public function __construct($language, $file)
    {
        $this->language = $language;
        $this->file = $file;
    }

    /**
     * Merge translations
     * @param array $new
     * @return array
     */
    public function merge(array $labels)
    {
        $new = $this->extract($labels);
        $existing = $this->removeUnused($new, $this->file === 'landing');

        /**
         * Merge existing and new
         * Keep existing translations and append new
         */
        $data = array_merge($new, $existing);

        /**
         * Sort alphabetically (keep keys)
         */
        ksort($data);

        return $data;
    }

    /**
     * Remove duplicates, remove empty, flip keys and values, reset values
     *
     * @param $data
     * @return array
     */
    protected function prepareData($data)
    {
        $data = array_unique($data);
        $data = array_filter($data);
        $data = array_flip($data);

        foreach ($data as $key => $value) {
            $data[$key] = $this->file . '.' . $key;
        }

        return $data;
    }

    /**
     * Remove not used labels from $existing
     * $new has all currently used labels
     *
     * @param $new array
     * @return array
     */
    protected function removeUnused($new, $debug = false)
    {
        $existing = $this->data($debug);

        foreach ($existing as $label => $value) {
            if (!array_key_exists($label, $new)) {
                unset($existing[$label]);
            }
        }

        return $existing;
    }

    /**
     * Get data/translations from localization file
     *
     * @return array|mixed
     */
    protected function data($debug = true)
    {
        $file = $this->file();
        $data = File::exists($file) ? include $file : [];

        return $data;
    }

    /**
     * Get path to localization file
     *
     * @return string
     */
    protected function file()
    {
        return app_path('lang/' . $this->language . '/' . $this->file . '.php');
    }

    protected function extract(array $labels)
    {
        $build = function (&$data, &$path = [], $level = 0) use (&$build) {
            foreach ($data as $key => &$value) {
                $path[] = $key;

                if (is_array($value)) {
                    $build($value, $path, $level + 1);
                    array_pop($path);
                    continue;
                }

                $value = $this->file . self::Separator . implode(self::Separator, $path);

                while($level < count($path)) {
                    array_pop($path);
                }
            }
        };

        $build($labels);

        return $labels;
    }
}