<?php

declare (strict_types = 1);

namespace App\Rules;

use App\Models\Conference;
use DateTime as Date;
use DateTimeZone;
use Illuminate\Contracts\Validation\Rule;

class StartTimeMustBeInRangeOfConference implements Rule
{

    public ?int $conference_id;

    public function __construct(?int $conference_id)
    {
        $this->conference_id = $conference_id;
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
        $conference = Conference::whereId($this->conference_id)->get();
        $conStartTime = $conference[0]->date->format('Y-m-d') . ' ' . $conference[0]->time;
        $conStartTime = new Date($conStartTime);
        $conStartTime->setTimezone(new DateTimeZone('GMT'));

        $conEndTime = $conference[0]->date->format('Y-m-d') . ' 23:59:59';
        $conEndTime = new Date($conEndTime);
        $conEndTime->setTimezone(new DateTimeZone('GMT'));
        $value = new Date($value);
        if ($value < $conStartTime) {
            return false;
        }
        if (($value->diff($conEndTime)->d >= 1) || ($value->diff($conStartTime)->d >= 1)) {
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
        return 'The :attribute must be in range of conference.';
    }
}
