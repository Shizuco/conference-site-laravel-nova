<?php

declare (strict_types = 1);

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Category extends Resource
{
    public static $model = \App\Models\Category::class;

    public static $title = 'id';

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

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Name', 'name')
            ->sortable()
            ->rules('required', 'max:255'),
            Select::make('Patend ID', 'parent_id')->options($this->getParentId()),
            Text::make('Created at', 'created_at')
            ->sortable()
            ->rules('required', 'max:255')
            ->exceptOnForms(),
            Text::make('Updated at', 'updated_at')
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
