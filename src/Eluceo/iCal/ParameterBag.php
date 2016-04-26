<?php

/*
 * This file is part of the eluceo/iCal package.
 *
 * (c) Markus Poerschke <markus@eluceo.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Eluceo\iCal;

class ParameterBag
{
    /**
     * The params.
     *
     * @var array
     */
    protected $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setParam($name, $value)
    {
        assert('is_string($name)', '$name parameter should be a string');

        $this->params[$name] = $value;
    }

    public function hasParam($name)
    {
        assert('is_string($name)', '$name parameter should be a string');

        return isset($this->params[$name]);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getParam($name)
    {
        assert('is_string($name)', '$name parameter should be a string');

        if (!isset($this->params[$name])) {
            throw new \InvalidArgumentException("There is no parameter named {$name}");
        }

        return $this->params[$name];
    }

    /**
     * Checks if there are any params.
     *
     * @return bool
     */
    public function hasParams()
    {
        return count($this->params) > 0;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $line = '';

        foreach ($this->params as $param => $paramValues) {
            if (!is_array($paramValues)) {
                $paramValues = [$paramValues];
            }
            foreach ($paramValues as $k => $v) {
                $paramValues[$k] = $this->escapeParamValue($v);
            }

            if ('' != $line) {
                $line .= ';';
            }

            $line .= $param . '=' . implode(',', $paramValues);
        }

        return $line;
    }

    /**
     * Returns an escaped string for a param value.
     *
     * @param string $value
     *
     * @return string
     */
    public function escapeParamValue($value)
    {
        $count = 0;
        $value = str_replace('\\', '\\\\', $value);
        $value = str_replace('"', '\"', $value, $count);
        $value = str_replace("\n", '\\n', $value);
        if (false !== strpos($value, ';') || false !== strpos($value, ',') || false !== strpos($value, ':') || $count) {
            $value = '"' . $value . '"';
        }

        return $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
