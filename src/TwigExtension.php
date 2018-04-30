<?php
/**
 * @link      https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   MIT
 */

namespace craft\storehours;

use Craft;
use craft\helpers\DateTimeHelper;

/**
 * Store-Hours Twig Extension
 */
class TwigExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'isOpen';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('isOpen', [$this, 'isOpen']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function isOpen($value)
    {
        $field = new Field();
        $slots = $field->getDailyTimeSlots($value);
        $currentDateTime = DateTimeHelper::toDateTime(DateTimeHelper::currentTimeStamp());

        foreach ($slots as $slot) {
            $timeSlots[] = $slot;
        }

        $status = null;

        foreach ($timeSlots as $key => $slot) {
            // Even slots (opening times)
            if (($key % 2) == 0) {
                if ($slot < $currentDateTime) {
                    $status = 'OPEN';
                } else {
                    $status = 'CLOSED';
                }
            }

            // Odd slots (closing times)
            if (($key % 2) != 0) {
                if ($slot < $currentDateTime) {
                    $status = 'CLOSED';
                } else {
                    $status = 'OPEN';
                }
            }
        }
        return $status;
    }
}