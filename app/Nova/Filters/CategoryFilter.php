<?php

namespace App\Nova\Filters;

use Gekich\NestedTreeFilter\NestedTreeFilter;

class CategoryFilter extends NestedTreeFilter
{
    public $filterModel = \App\Models\Category::class; // - nested tree model
    public $filterRelation = 'categories'; // - relation that filter uses
    public $name = 'Categories filter'; // - filter name
    public $attribute = 'Categories list';
    public $idKey = 'id'; // - id column
    public $labelKey = 'name'; // - label column name
    public function authorize()
    {
        return true;
    }
    public function isShownOnUpdate()
    {
        return false;
    }
    public function isShownOnDetail()
    {
        return true;
    }
    public function isShownOnPreview()
    {
        return true;
    }
    public function isShownOnIndex()
    {
        return false;
    }
    public function isShownOnCreation()
    {
        return false;
    }
    public $placeholder = 'Select...';
    public $multiple = true;
}
