<?php

declare (strict_types = 1);

namespace App\Nova;

use Dniccum\PhoneNumber\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Listeners extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'surname', 'role', 'email', 'phone', 'password', 'country', 'birthday', 'created_at', 'updated_at',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('role', 'listener');
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

            Gravatar::make()->maxWidth(50),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('surname')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('role')
                ->options([
                    'listener' => 'listener'
                ])
                ->default('listener')
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
        return [
            
        ];
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
