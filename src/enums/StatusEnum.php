<?php
namespace Botble\webrobotdashboard\enums;
use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static StatusEnum SETUP()
 * @method static StatusEnum MAINTANANCE()
 * @method static StatusEnum COMPLETED()
 * @method static StatusEnum SCRAPINGINPROGRESS()
 */
class StatusEnum extends Enum
{
    public const SETUP = 'Setup';
    public const MAINTANANCE = 'Maintanance';
    public const COMPLETED = 'Completed';
    public const SCRAPINGINPROGRESS = 'ScrapingInProgress';

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
            case self::SETUP:
                return Html::tag('span', self::SETUP()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::MAINTANANCE:
                return Html::tag('span', self::MAINTANANCE()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::COMPLETED:
                return Html::tag('span', self::COMPLETED()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::SCRAPINGINPROGRESS:
                return Html::tag('span', self::SCRAPINGINPROGRESS()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
