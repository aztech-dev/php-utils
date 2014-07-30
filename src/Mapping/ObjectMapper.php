<?php

namespace Aztech\Util\Mapping;

use \Aztech\Util\DotNotation\DotNotationParser;
use \Aztech\Util\DotNotation\DotNotationResolver;

class ObjectMapper
{

    private $mappings = array();

    private $constants = array();

    public function addMapping($sourceKey, $targetKey, $required = false)
    {
        $this->mappings[$sourceKey] = array('target' => $targetKey, 'req' => $required);
    }

    public function addConstantMapping($targetKey, $value)
    {
        $this->constants[$targetKey] = $value;
    }

    public function map($source, & $target)
    {
        foreach ($this->mappings as $sourceKey => $targetData) {
            $this->mapItem($source, $target, $sourceKey, $targetData);
        }

        foreach ($this->constants as $targetKey => $value) {
            $this->assign($target, $targetKey, $value);
        }
    }

    private function mapItem(& $source, & $target, $sourceKey, array & $targetData)
    {
        $targetKey = $targetData['target'];
        $required = $targetData['req'];
        $exists = $this->hasPropertyOrIndex($source, $sourceKey);

        if (! $required && ! $exists) {
            return;
        }

        if (! $exists) {
            throw new \InvalidArgumentException('Source has no such property or index : "' . $sourceKey . "'");
        }

        $this->assign($target, $targetKey, $this->read($source, $sourceKey));
    }

    private function hasPropertyOrIndex($object, $key)
    {
        return DotNotationResolver::propertyOrIndexExists($object, $key);
    }

    private function read($source, $key)
    {
        return DotNotationResolver::resolve($source, $key);
    }

    private function assign(& $target, $targetKey, $value)
    {
        if (is_array($target)) {
            $target[$targetKey] = $value;
        }
        else {
            $target->{$targetKey} = $value;
        }
    }
}
