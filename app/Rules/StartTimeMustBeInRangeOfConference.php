<?php

namespace App\Rules;

use App\Models\Conference;
use DateTime;
use DateTimeZone;
use Illuminate\Contracts\Validation\Rule;

class StartTimeMustBeInRangeOfConference implements Rule
{

    public int $conference_id;

    public function __construct(int $id){
        $this->conference_id = $id;
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
        $conStartTime = new DateTime($conference[0]->date);
        $conStartTime->setTimezone(new DateTimeZone('GMT'));
        $conEndTime = new DateTime($conference[0]->date);
        $conEndTime->setTimezone(new DateTimeZone('GMT'));
        $value = new DateTime($value);
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
        return 'The :attribute must be in rahge of conference.';
    }
}
