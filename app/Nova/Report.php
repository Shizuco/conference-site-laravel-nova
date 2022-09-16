<?php

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

class Report extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Report::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'thema', 'start_time', 'zoom_meeting_id', 'end_time', 'duration', 'user_id', 'category_id', 'conference_id', 'description', 'presentation', 'created_at', 'updated_at',
    ];

    public function getAllAnnoucers()
    {
        $list = array();
        foreach (\App\Models\User::where('role', 'annoucer')->get() as $annoucer) {
            $list[$annoucer['id']] = $annoucer['name'] . ' ' . $annoucer['surname'];
        }
        return $list;
    }

    public function getAllConferences()
    {
        $list = array();
        foreach (\App\Models\Conference::all() as $conference) {
            $list[$conference['id']] = $conference['title'];
        }
        return $list;
    }

    public function getAllCategories()
    {
        $list = array();
        foreach (\App\Models\Category::all() as $category) {
            $list[$category['id']] = $category['name'];
        }
        return $list;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Select::make('Category ID', 'category_id')->options(
                $this->getAllCategories()
            )->rules('required'),

            Select::make('Conference ID', 'conference_id')->options(
                $this->getAllConferences()
            )->rules('required'),

            Select::make('User ID', 'user_id')->options(
                $this->getAllAnnoucers()
            )->rules('required'),

            Text::make('Zoom', 'zoom_meeting_id')
                ->placeholder('online or offline')
                ->creationRules(new StringMustBeOneOfTwo)
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
                    if (preg_match('/[0-9]+/', $request->zoom_meeting_id) === 1) {
                        $zoom = new \App\Http\Controllers\MeetingController;
                        $data = [
                            'topic' => $request->thema,
                            'start_time' => new Date($request->start_time),
                            'duration' => intval($request->duration / 60),
                            "host_video" => "true",
                            "participant_video" => "true",
                        ];
                        $zoom->update($request->zoom_meeting_id, $data);
                    }
                }),

            Text::make('Thema', 'thema')
                ->sortable()
                ->rules('required', 'max:255'),

            DateTime::make('Start time', 'start_time')
                ->rules('required', new StartTimeMustBeInRangeOfConference($request->conference_id),
                    new ReportDurationMustBeLessThenHour($request->start_time, $request->end_time),
                    new DateMustBeAvailable($request->conference_id),
                    new StartTimeMustBeLessThanEndTime($request->start_time, $request->end_time)
                ),

            DateTime::make('End time', 'end_time')
                ->sortable()
                ->rules('required', 'max:255', new StartTimeMustBeInRangeOfConference($request->conference_id),
                    new ReportDurationMustBeLessThenHour($request->start_time, $request->end_time),
                    new DateMustBeAvailable($request->conference_id),
                    new StartTimeMustBeLessThanEndTime($request->start_time, $request->end_time)
                ),

            Textarea::make('Description', 'description')
                ->sortable()
                ->rules('required', 'max:255'),

            File::make('Presentation', 'presentation')
                ->disk('public')
                ->storeAs(fn (Request $request) => $request->presentation->getClientOriginalName())
                ->storeOriginalName('presentation')
                ->creationRules('required', 'max:10240')
                ->acceptedTypes('.ppt, .pptx'),

            Text::make('Created at', 'created_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),

            Text::make('Updated at', 'updated_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),

            Text::make('Duration', 'duration')
                ->fillUsing(function ($request, $model, $attribute) {
                    $end_time = new Date($request->end_time);
                    $start_time = new Date($request->start_time);
                    $model->{$attribute} = $end_time->getTimestamp() - $start_time->getTimestamp();
                }),

            Text::make('Join URL', 'join_url', function () {
                $zoom = new \App\Http\Controllers\MeetingController;
                return $zoom->get($this->zoom_meeting_id)['data']['join_url'];
            })->onlyOnDetail()->copyable(),

            Text::make('Start URL', 'start_url', function () {
                $zoom = new \App\Http\Controllers\MeetingController;
                return $zoom->get($this->zoom_meeting_id)['data']['start_url'];
            })->onlyOnDetail()->copyable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
