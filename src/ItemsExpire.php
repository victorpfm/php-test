<?php

namespace Live\Collection;

trait ItemsExpire
{
    /**
     * Checks wether the index is valid or has expired
     */
    protected function hasExpired(string $index)
    {
        return time() >= $this->data[$index]['validUntil'];
    }

    /**
     * Removes expired itens from the collection
     *
     * @return object
     */
    protected function clearExpiredItems()
    {
        foreach ($this->data as $index => $item) {
            if ($this->hasExpired($index)) {
                unset($this->data[$index]);
            }
        }

        return $this;
    }
}
