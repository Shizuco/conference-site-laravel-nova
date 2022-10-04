<?php

declare (strict_types = 1);

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Trinityrank\GoogleMapWithAutocomplete\TRMap;

class Conference extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Conference::class;

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
        'id', 'title', 'date', 'time', 'category_id', 'country', 'latitude', 'longitude', 'created_at', 'updated_at',
    ];

    public function getAllCategories()
    {
        $list = array();
        foreach (\App\Models\Category::where('parent_id', null)->get() as $category) {
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
            Text::make('Title', 'title')
                ->sortable()
                ->rules('required', 'max:255'),
            Date::make('Date', 'date')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Time', 'time')
                ->placeholder('##:##:##')
                ->rules('required', 'date_format:"H:i:s"')
                ->help('hh:mm:ss'),
            Select::make('Category ID', 'category_id')->options(
                $this->getAllCategories()
            )->rules('required'),
            Select::make('Country')->options([
                'Japan' => 'Japan',
                'Russia' => 'Russia',
                'Ukraine' => 'Ukraine',
                'Belarus' => 'Belarus',
                'Canada' => 'Canada',
                'France' => 'France',
                'England' => 'England',
                'USA' => 'USA',
                'China' => 'China',
                'Korea' => 'Korea',
            ])->rules('required'),
            Text::make('Created at', 'created_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),
            Text::make('Updated at', 'updated_at')
                ->sortable()
                ->rules('required', 'max:255')
                ->exceptOnForms(),
            TRMap::make('Map'),
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
