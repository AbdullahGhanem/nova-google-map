<?php

namespace Laravel\Nova\Fields;

class Field
{
    public $component;
    public $attribute;
    public $name;
    public $meta = [];

    public function __construct(string $name, ?string $attribute = null)
    {
        $this->name = $name;
        $this->attribute = $attribute;
    }

    public function withMeta(array $meta): static
    {
        $this->meta = array_merge($this->meta, $meta);
        return $this;
    }
}
