<?php

namespace Victoire\Widget\FormBundle\Resolver;

use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;
use Victoire\Bundle\CoreBundle\Helper\CurrentViewHelper;
use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Bundle\WidgetBundle\Resolver\BaseWidgetContentResolver;
use Victoire\Widget\FormBundle\Entity\WidgetForm;

class WidgetFormContentResolver extends BaseWidgetContentResolver
{
    protected $currentViewHelper;

    /**
     * WidgetFormContentResolver constructor.
     *
     * @param CurrentViewHelper $currentViewHelper
     */
    public function __construct(CurrentViewHelper $currentViewHelper) {
        $this->currentViewHelper = $currentViewHelper;
    }

    /**
     * Get the static content of the widget.
     *
     * @param Widget $widget
     *
     * @return string
     */
    public function getWidgetStaticContent(Widget $widget)
    {
        $parameters = parent::getWidgetStaticContent($widget);
        $parameters = self::addMonthParameters($widget, $parameters);

        return $parameters;
    }

    protected function addMonthParameters(Widget $widget, $parameters)
    {
        $currentView = $this->currentViewHelper->getCurrentView();
        $locale = $currentView->getCurrentLocale();
        $formatter = new IntlDateFormatter($locale, 0, 0);

        $months = [];
        $date = new \DateTime();
        foreach (range(1, 12) as $monthNumber) {
            $date->setDate(2012, $monthNumber, 1);
            $formatter->setPattern('MMMM');
            $months[$monthNumber] = $formatter->format($date);
        }

        $parameters = array_merge($parameters, [
            'months' => $months
        ]);

        return $parameters;
    }
}
