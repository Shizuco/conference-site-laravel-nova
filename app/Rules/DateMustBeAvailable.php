<?php

namespace App\Rules;

use App\Models\Conference;
use App\Models\Report;
use DateTime as Date;
use DateTimeZone;
use Illuminate\Contracts\Validation\Rule;

class DateMustBeAvailable implements Rule
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
        $reports = Report::All()->where('conference_id', $this->conference_id);
        $value = new Date($value);
        foreach ($reports as $report) {
            $startTimeExist = new Date($report->start_time);
            $startTimeExist->setTimezone(new DateTimeZone('GMT'));
            $endTimeExist = new Date($report->end_time);
            $endTimeExist->setTimezone(new DateTimeZone('GMT'));
            if ($this->isInRange($value, $startTimeExist, $endTimeExist) === true) {
                return false;
            }
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
        $time = $this->nearestTime($this->conference_id);
        return 'Nearest time to start is ' . $time;

    }

    private function isInRange(Date $dateToCheck, Date $startDate, Date $endDate)
    {
        return $dateToCheck >= $startDate && $dateToCheck <= $endDate;
    }

    private function nearestTime(int $id)
    {
        $conference = Conference::whereId($id)->get();
        $results = Report::orderBy('start_time')->where('conference_id', $id)->get();
        $start_time = explode(" ", $conference[0]->date)[0];
        $start_time = $start_time . ' ' . $conference[0]->time;
        $start_time = new Date($start_time);
        $end_time = 0;
        for ($a = 0; $a < count($results); $a++) {
            if ($a !== 0 && $a !== count($results) - 1) {
                $start_time = new Date($results[$a + 1]->start_time);
            }
            if ($a === count($results) - 1) {
                $end_time = explode(" ", $conference[0]->date)[0];
                $end_time = $end_time . ' 23:59:59';
                $end_time = new Date($end_time);
                $start_time = new Date($results[$a]->end_time);
            } else if ($a === 0) {
                $end_time = new Date($results[$a]->start_time);
            } else {
                $end_time = new Date($results[$a]->end_time);
            }
            $interval = $end_time->diff($start_time);
            $err = $interval->format('%i') >= 10;
            if ($err) {
                if ($a === 0) {
                    return $start_time->format('Y-m-d H:i:s');
                } else {
                    return $results[$a]->end_time;
                }
            }
        }
    }
}
