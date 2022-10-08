<?php

declare (strict_types = 1);

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Comment extends Resource
{
    public static $model = \App\Models\Comment::class;

    public static $title = 'id';

    public static $search = [
        'id', 'user_id', 'report_id', 'conference_id', 'comment', 'created_at', 'updated_at',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('User ID', 'user_id')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Report ID', 'report_id')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Conference ID', 'conference_id')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Comment', 'comment')
                ->sortable()
                ->rules('required', 'max:255'),
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
