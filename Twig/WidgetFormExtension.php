<?php
namespace Victoire\Widget\FormBundle\Twig;

class WidgetFormExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'camelize' => new \Twig_SimpleFilter('camelize', array($this, 'camelizeFilter')),
        );
    }

    /**
     * register twig functions
     *
     * @return array The list of extensions
     */
    public function getFunctions()
    {
        return array(
            'data_unserialize'           => new \Twig_Function_Method($this, 'dataUnserialize', array('is_safe' => array('html'))),
        );
    }

    public function dataUnserialize($data)
    {
        if (is_string($data) && $data != 'N;') {
            try {
                return unserialize($data);
            } catch (\Exception $e) {
                throw new \Exception(sprintf("Please check the validity of the proposal value %s. %s", $data, $e->getMessage()));
            }
        }

        return '';
    }

    public function camelizeFilter($id)
    {
        return strtr(ucwords(strtr($id, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => ''));
    }

    public function getName()
    {
        return 'victoire_form_widget_twig_extension';
    }
}
