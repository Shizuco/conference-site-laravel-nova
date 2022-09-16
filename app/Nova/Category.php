<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
//use App\Nova\Filters\CategoryFilter;
//use Seche\NovaJstree\Jstree;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Category::class;

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
        'id', 'name', 'parent_id', 'created_at', 'updated_at',
    ];

    private function getParentId()
    {
        $list = array();
        foreach (\App\Models\Category::where('parent_id', null)->get() as $category) {
            $list[$category['id']] = $category['name'];
        }
        return $list;
    }

    private function get()
    {
        return \App\Models\Category::get();
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
            Text::make('Name', 'name')
            ->sortable()
            ->rules('required', 'max:255'),
            //CategoryFilter::make(),
            Select::make('Patend ID', 'parent_id')->options($this->getParentId()),
            Text::make('Created at', 'created_at')
            ->sortable()
            ->rules('required', 'max:255')
            ->exceptOnForms(),
            Text::make('Updated at', 'updated_at')
            ->sortable()
            ->rules('required', 'max:255')
            ->exceptOnForms(),
            /*Jstree::make(__('Category'), \App\Models\Category::class)
             ->theme(['dots' => true, 'stripes' => false, 'icons' => false])
             ->checkbox()
             ->search()
             ->data(['1', '2', '3']),*/
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