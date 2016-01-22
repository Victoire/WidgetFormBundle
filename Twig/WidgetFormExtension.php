<?php

namespace Victoire\Widget\FormBundle\Twig;

class WidgetFormExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('camelize', [$this, 'camelizeFilter']),
        ];
    }

    /**
     * register twig functions.
     *
     * @return array The list of extensions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('data_unserialize', [$this, 'dataUnserialize'], ['is_safe' => ['html']]),
        ];
    }

    public function dataUnserialize($data)
    {
        if (is_string($data) && $data != 'N;') {
            try {
                return unserialize($data);
            } catch (\Exception $e) {
                throw new \Exception(sprintf('Please check the validity of the proposal value %s. %s', $data, $e->getMessage()));
            }
        }

        return '';
    }

    public function camelizeFilter($id)
    {
        return strtr(ucwords(strtr($id, ['_' => ' ', '.' => '_ ', '\\' => '_ '])), [' ' => '']);
    }

    public function getName()
    {
        return 'victoire_form_widget_twig_extension';
    }
}
