<?php namespace Rolice\LangSync;

/**
 * Class LabelsCollection
 * @package Rolice\LangSync
 */
class LabelsCollection
{
    /**
     * @var array
     */
    private $collection = [];

    /**
     * Set collection items
     *
     * @param $collection
     * @return $this
     */
    public function set($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection items
     *
     * @return array
     */
    public function get()
    {
        return $this->collection;
    }
}