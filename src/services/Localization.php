<?php namespace Rolice\LangSync;

use File;

/**
 * Class Localization
 * @package Rolice\LangSync
 */
class Localization
{
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
    public function merge(array $new)
    {
        $new = $this->prepareData($new);
        $existing = $this->removeNotUsed($this->data(), $new);

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
            $data[$key] = $this->file.'.'.$key;
        }

        return $data;
    }

    /**
     * Remove not used labels from $existing
     * $new has all currently used labels
     *
     * @param $existing array
     * @param $new array
     * @return array
     */
    protected function removeNotUsed($existing, $new)
    {
        foreach ($existing as $label => $value) {
            if (! array_key_exists($label, $new)) {
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
    protected function data()
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
}