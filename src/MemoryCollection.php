<?php

namespace Live\Collection;

/**
 * Memory collection
 *
 * @package Live\Collection
 */
class MemoryCollection implements CollectionInterface
{
    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        $this->clearExpiredItems();
        
        if (!$this->has($index)) {
            return $defaultValue;
        }

        return $this->data[$index]['value'];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, $ttl = 60)
    {
        $this->data[$index] = [
            'value' => $value,
            'validUntil' => time() + $ttl
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        return isset($this->data[$index]);
    }

    /**
     * Checks wether the index is valid or has expired
     */
    protected function isValid(string $index)
    {
        return time() < $this->data[$index]['validUntil'];
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        $this->data = [];
    }

    /**
     * Removes expired itens from the collection
     * 
     * @return void
     */
    protected function clearExpiredItems()
    {
        foreach ($this->data as $index => $item) {
            if (!$this->isValid($index)) {
                unset($this->data[$index]);
            }
        }
    }
}
