<?php

declare (strict_types = 1);

namespace App\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ZoomMeeting extends Resource
{
    public static $model = \App\Models\ZoomMeeting::class;

    public static $title = 'id';

    public static $search = [
        'id', 'topic', 'type', 'agenda', 'start_time', 'duration', 'timezone', 'start_url', 'join_url', 'created_at', 'updated_at',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('topic')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('type')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('agenda')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('start_time')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('duration')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('timezone')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('start_url')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('join_url')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('created_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),

            Text::make('updated_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),
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
