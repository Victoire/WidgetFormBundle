<?php

namespace Victoire\Widget\FormBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class WidgetFormeMailEvent extends Event
{
    private $data;

    /**
     * WidgetFormeMailEvent constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Add data before POST form data
     *
     * @param $label
     * @param $value
     */
    public function prependData($label, $value)
    {
        $newField = ['label' => $label, 'value' => $value];
        $this->data = array_merge($newField, $this->data);
    }

    /**
     * Add data after POST form data
     *
     * @param $label
     * @param $value
     */
    public function appendData($label, $value)
    {
        $newField = ['label' => $label, 'value' => $value];
        $this->data = array_merge($this->data, $newField);
    }
}
