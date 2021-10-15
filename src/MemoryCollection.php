<?php

namespace Live\Collection;

/**
 * Memory collection
 *
 * @package Live\Collection
 */
class MemoryCollection implements CollectionInterface
{
    use ItemsExpire;

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
        $this->clearExpiredItems();
        
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
     * {@inheritDoc}
     */
    public function clean()
    {
        $this->data = [];
    }
}
