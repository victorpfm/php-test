<?php

namespace Live\Collection;

/**
 * File collection
 *
 * @package Live\Collection
 */
class FileCollection implements CollectionInterface
{
    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    /**
     * Collection name
     *
     * @var string
     */
    protected $name = 'collection';

    /**
     * Constructor
     */
    public function __construct($name = 'collection')
    {
        $this->name = $name;

        if (!$this->collectionFileExists()) {
            $this->createEmptyFile();
        }

        $this->parseFile();
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        if (!$this->has($index)) {
            return $defaultValue;
        }

        return $this->data[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value)
    {
        $this->data[$index] = $value;

        $this->persist();
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        return isset($this->data[$index]);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Persists the current data to the collection file
     *
     * @return void
     */
    protected function persist()
    {
        file_put_contents($this->filePath(), json_encode($this->data));
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        $this->data = [];
        $this->createEmptyFile();
    }

    /**
     * Returns a path for the collection file
     *
     * @return string
     */
    protected function filePath()
    {
        return dirname(__DIR__, 1) . "/database/{$this->name}.json";
    }

    /**
     * Checks if a collection file exists
     *
     * @return bool
     */
    protected function collectionFileExists()
    {
        return file_exists($this->filePath($this->name));
    }

    /**
     * Create an empty collection file
     *
     * @return void
     */
    protected function createEmptyFile()
    {
        file_put_contents($this->filePath(), "{}");
    }

    /**
     * Parses a collection file
     *
     * @return void
     */
    protected function parseFile()
    {
        $this->data = json_decode(
            file_get_contents($this->filePath()),
            true
        );
    }
}
