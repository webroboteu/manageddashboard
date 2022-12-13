<?php
namespace Botble\webrobotdashboard\enums;
use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static FrequencyEnum DAILY()
 * @method static FrequencyEnum WEEKLY()
 * @method static FrequencyEnum BWEEKLY()
 * @method static FrequencyEnum MONTLY()
 */
class FrequencyEnum extends Enum
{
    public const DAILY = 'Daily';
    public const WEEKLY = 'Weekly';
    public const BWEEKLY = 'BWeekly';
    public const MONTLY = 'Montly';

    /**
     * @var string
     */
    public static $langPath = 'core/base::enums.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::DAILY:
                return Html::tag('span', self::DAILY()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::WEEKLY:
                return Html::tag('span', self::WEEKLY()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::BWEEKLY:
                return Html::tag('span', self::BWEEKLY()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::MONTLY:
                return Html::tag('span', self::MONTLY()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}