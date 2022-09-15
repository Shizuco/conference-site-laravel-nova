<?php

namespace App\Rules;
use DateTime as Date;

use Illuminate\Contracts\Validation\Rule;

class ReportDurationMustBeLessThenHour implements Rule
{
    public ?string $start_time;
    public ?string $end_time;

    public function __construct(?string $start_time, ?string $end_time)
    {
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $end_time = new Date($this->end_time);
        $start_time = new Date($this->start_time);
        $interval = ($start_time->diff($end_time));
        if ($interval->format('%h') >= 1) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Maximum time of report should be less than hour.';
    }
}
