<?php

declare (strict_types = 1);

namespace App\Nova;

use Dniccum\PhoneNumber\PhoneNumber;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Announcer extends Resource
{
    public static $model = \App\Models\User::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'surname', 'role', 'email', 'phone', 'password', 'country', 'birthday', 'created_at', 'updated_at',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('role', 'annoucer');
    }

    public function fields(NovaRequest $request)
    {
        return [
            Gravatar::make()->maxWidth(50),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('surname')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('role')
                ->options([
                    'annoucer' => 'annoucer',
                ])
                ->default('annoucer')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            PhoneNumber::make('Phone')->disableValidation()->rules('required')->format('+38(###)-###-####'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

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

            Date::make('birthday')
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
