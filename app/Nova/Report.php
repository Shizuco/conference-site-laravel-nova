<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Rules\StartTimeMustBeInRangeOfConference;

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
        'id', 'thema', 'start_time', 'end_time', 'duration', 'user_id', 'category_id', 'conference_id', 'description', 'presentation', 'created_at', 'updated_at',
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

            Select::make('category_id')->options(
                $this->getAllCategories()
            )->rules('required'),

            Select::make('conference_id')->options(
                $this->getAllConferences()
            )->rules('required'),

            Select::make('user_id')->options(
                $this->getAllAnnoucers()
            )->rules('required'),

            Text::make('thema')
                ->sortable()
                ->rules('required', 'max:255'),

            DateTime::make('start_time')
                ->rules('required'),

            DateTime::make('end_time')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('description')
                ->sortable()
                ->rules('required', 'max:255'),

            File::make('presentation')
                ->rules('required', 'max:10240')
                ->acceptedTypes('.ppt, .pptx'),

            Text::make('created_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),

            Text::make('updated_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),
                
            Text::make('duration')->rules('required'),
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
