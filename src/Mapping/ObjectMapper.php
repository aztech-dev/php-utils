<?php

/**
 *
 * @author thibaud
 */
namespace Aztech\Util\Mapping;

use \Aztech\Util\DotNotation\DotNotationResolver;

/**
 * @author thibaud
 *
 */
class ObjectMapper
{

    private $resolver = null;

    private $mappings = array();

    private $constants = array();

    public function __construct(DotNotationResolver $resolver = null)
    {
        if ($resolver == null) {
            $resolver = new DotNotationResolver();
        }

        $this->resolver = $resolver;
    }

    /**
     *
     * @param string $sourceKey
     * @param string $targetKey
     * @param bool $required
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
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
        return $this->resolver->propertyOrIndexExists($object, $key);
    }

    private function read($source, $key)
    {
        return $this->resolver->resolve($source, $key);
    }

    private function assign(& $target, $targetKey, $value)
    {
        if (is_array($target)) {
            $target[$targetKey] = $value;

            return;
        }

        $target->{$targetKey} = $value;
    }
}
