<?php

declare (strict_types = 1);

namespace App\Nova;

use App\Rules\DateMustBeAvailable;
use App\Rules\ReportDurationMustBeLessThenHour;
use App\Rules\StartTimeMustBeInRangeOfConference;
use App\Rules\StartTimeMustBeLessThanEndTime;
use App\Rules\StringMustBeOneOfTwo;
use DateTime as Date;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Conference;
use App\Models\User;

class Report extends Resource
{
    public static $model = \App\Models\Report::class;

    public static $title = 'id';

    public static $search = [
        'id', 'thema', 'start_time', 'zoom_meeting_id', 'end_time', 'duration', 'user_id', 'category_id', 'conference_id', 'description', 'presentation', 'created_at', 'updated_at',
    ];

    public function getAllAnnoucers()
    {
        $list = array();
        foreach (User::where('role', 'annoucer')->get() as $annoucer) {
            $list[$annoucer['id']] = $annoucer['name'] . ' ' . $annoucer['surname'];
        }
        return $list;
    }

    public function getAllConferences()
    {
        $list = array();
        foreach (Conference::get() as $conference) {
            $list[$conference['id']] = $conference['title'];
        }
        return $list;
    }

    public function getAllCategories()
    {
        $list = array();
        foreach (Category::get() as $category) {
            $list[$category['id']] = $category['name'];
        }
        return $list;
    }

    public function fields(NovaRequest $request)
    {
        return [

            Select::make('Category', 'category_id')->options(
                $this->getAllCategories()
            )->rules('required')->hideFromIndex(),

            Select::make('Conference', 'conference_id')->options(
                $this->getAllConferences()
            )->rules('required')->hideFromIndex(),

            Select::make('User', 'user_id')->options(
                $this->getAllAnnoucers()
            )->rules('required')->hideFromIndex(),

            Text::make('Thema', 'thema')
                ->sortable()
                ->rules('required', 'max:255'),

            DateTime::make('Start time', 'start_time')
                ->rules('required', new StartTimeMustBeInRangeOfConference(intval($request->conference_id)),
                    new ReportDurationMustBeLessThenHour($request->start_time, $request->end_time),
                    new DateMustBeAvailable(intval($request->conference_id), $this->id),
                    new StartTimeMustBeLessThanEndTime($request->start_time, $request->end_time)
                ),

            DateTime::make('End time', 'end_time')
                ->sortable()
                ->rules('required', 'max:255', new StartTimeMustBeInRangeOfConference(intval($request->conference_id)),
                    new ReportDurationMustBeLessThenHour($request->start_time, $request->end_time),
                    new DateMustBeAvailable(intval($request->conference_id), $this->id),
                    new StartTimeMustBeLessThanEndTime($request->start_time, $request->end_time)
                ),

            Textarea::make('Description', 'description')
                ->sortable()
                ->rules('required', 'max:255'),

            File::make('Presentation', 'presentation')
                ->disk('public')
                ->storeAs(fn(Request $request) => $request->presentation->getClientOriginalName())
                ->storeOriginalName('presentation')
                ->creationRules('required', 'max:10240')
                ->acceptedTypes('.ppt, .pptx'),

            Text::make('Zoom', 'zoom_meeting_id')
                ->placeholder('online or offline')
                ->creationRules(new StringMustBeOneOfTwo)
                ->hideWhenUpdating()
                ->fillUsing(function ($request, $model, $attribute) {
                    if ($request->zoom_meeting_id == 'online') {
                        $data = [
                            'topic' => $request->thema,
                            'start_time' => new Date($request->start_time),
                            'duration' => intval($request->duration / 60),
                            "host_video" => "true",
                            "participant_video" => "true",
                        ];
                        $meeting = new \App\Http\Controllers\MeetingController;
                        $zoom = $meeting->store($data);
                        $model->{$attribute} = $zoom->original['data']['id'];
                    }
                    if ($request->zoom_meeting_id == null) {
                        $model->{$attribute} = null;
                    } else if (preg_match('/[0-9]+/', $request->zoom_meeting_id) === 1) {
                        $zoom = new \App\Http\Controllers\MeetingController;
                        $data = [
                            'topic' => $request->thema,
                            'start_time' => new Date($request->start_time),
                            'duration' => intval($request->duration / 60),
                            "host_video" => "true",
                            "participant_video" => "true",
                        ];
                        $zoom->update(intval($request->zoom_meeting_id), $data);
                    }
                }),

            Text::make('Created at', 'created_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),

            Text::make('Updated at', 'updated_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),

            Text::make('Duration/in sec', 'duration')
                ->fillUsing(function ($request, $model, $attribute) {
                    $end_time = new Date($request->end_time);
                    $start_time = new Date($request->start_time);
                    $model->{$attribute} = $end_time->getTimestamp() - $start_time->getTimestamp();
                })->hideWhenUpdating(),

            Text::make('Join URL', 'join_url', function () {
                $zoom = new \App\Http\Controllers\MeetingController;
                if ($zoom->get($this->zoom_meeting_id)['data'] == null) {
                    return null;
                }
                return $zoom->get($this->zoom_meeting_id)['data']['join_url'];
            })->onlyOnDetail()->copyable()->nullable(),

            Text::make('Start URL', 'start_url', function () {
                $zoom = new \App\Http\Controllers\MeetingController;
                if ($zoom->get($this->zoom_meeting_id)['data'] == null) {
                    return null;
                }
                return $zoom->get($this->zoom_meeting_id)['data']['start_url'];
            })->onlyOnDetail()->copyable()->nullable(),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }
}
