<?php

namespace Ispgo\Ckeditor;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\SupportsDependentFields;

class Ckeditor extends Field
{
    use SupportsDependentFields;

    public $component = 'ckeditor';
}
