<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
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
        'id', 'thema', 'start_time', 'end_time', 'user_id', 'conference_id', 'description', 'presentation', 'created_at', 'updated_at',
    ];

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
            Text::make('conference_id')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('user_id')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('thema')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('start_time')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('end_time')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('description')
                ->sortable()
                ->rules('required', 'max:255'),
            File::make('presentation')
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
