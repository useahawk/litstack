<?php

namespace Fjord\Crud\Config;

use Illuminate\Support\Str;
use Fjord\Crud\Models\FormField;
use Fjord\Crud\Config\Traits\HasCrudForm;

abstract class FormConfig
{
    use HasCrudForm;

    /**
     * Form field model class.
     *
     * @var string
     */
    public $model = FormField::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller;

    /**
     * Form collection name.
     *
     * @var string
     */
    public $collection;

    /**
     * Form name, is used for routing.
     *
     * @var string
     */
    public $formName;

    /**
     * Create new FormConfig instance.
     */
    public function __construct()
    {
        $split = explode(
            '\\',
            last(explode('Config\\Form\\', static::class))
        );
        $this->collection = strtolower($split[0]);

        $this->model = FormField::class;
    }

    /**
     * Get crud route prefix.
     *
     * @return string $route
     */
    public function routePrefix()
    {
        $collection = strtolower($this->collection);
        $formName = strtolower($this->formName);
        return "form/{$collection}/{$formName}";
    }

    public function permissions()
    {
        $user = fjord_user();
        $controller = new $this->controller;
        return [
            'read' => $controller->authorize($user, 'read'),
            'update' => $controller->authorize($user, 'update'),
        ];
    }

    /**
     * Model singular and plural name.
     *
     * @return array $names
     */
    public function names()
    {
        return [
            'singular' => ucfirst(Str::singular($this->formName)),
            'plural' => ucfirst(Str::plural($this->formName)),
        ];
    }
}
