<?php

declare (strict_types = 1);

namespace App\Rules;

use App\Models\Conference;
use App\Models\Report;
use DateTime as Date;
use DateTimeZone;
use Illuminate\Contracts\Validation\Rule;

class DateMustBeAvailable implements Rule
{
    public ?int $conference_id;
    public ?int $report_id;

    public function __construct(?int $conference_id, ?int $report_id)
    {
        $this->conference_id = $conference_id;
        $this->report_id = $report_id;
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
        $value = new Date($value);
        if ($this->report_id != null) {
            $currentReport = Report::where('id', $this->report_id)->firstOfFail();
            $currentReportStartTime = new Date($currentReport->start_time->format('Y-m-d H:i:s'));
            $currentReportEndTime = new Date($currentReport->end_time->format('Y-m-d H:i:s'));
            if (($currentReportStartTime == $value && $attribute == 'start_time') || ($currentReportEndTime == $value && $attribute == 'end_time')) {
                return true;
            }
        }
        $reports = Report::where('conference_id', $this->conference_id)->get();
        foreach ($reports as $report) {
            $startTimeExist = new Date($report->start_time->format('Y-m-d H:i:s'));
            $startTimeExist->setTimezone(new DateTimeZone('GMT'));
            $endTimeExist = new Date($report->end_time->format('Y-m-d H:i:s'));
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
        $conference = Conference::where('id', $id)->firstOfFail();
        $results = Report::orderBy('start_time')->where('conference_id', $id)->get();
        $start_time = $conference->date->format('Y-m-d') . ' ' . $conference->time;
        $start_time = new Date($start_time);
        $end_time = 0;
        for ($a = 0; $a < count($results); $a++) {
            if ($a !== 0 && $a !== count($results) - 1) {
                $start_time = new Date($results[$a + 1]->start_time->format('Y-m-d H:i:s'));
            }
            if ($a === count($results) - 1) {
                $end_time = $conference->date->format('Y-m-d') . ' 23:59:59';
                $end_time = new Date($end_time);
                $start_time = new Date($results[$a]->end_time->format('Y-m-d H:i:s'));
            } else if ($a === 0) {
                $end_time = new Date($results[$a]->start_time->format('Y-m-d H:i:s'));
            } else {
                $end_time = new Date($results[$a]->end_time->format('Y-m-d H:i:s'));
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
