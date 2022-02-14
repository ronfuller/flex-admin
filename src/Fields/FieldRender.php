<?php

namespace Psi\FlexAdmin\Fields;

trait FieldRender
{
    /**
     * Name of component, override on child
     *
     * @var string|null
     */
    public string|null $component;

    /**
     * Associative array of components
     */
    protected array|null $components;

    /**
     * Associates resource with a distinct panel
     *
     * @var string
     */
    protected $panel;


    /**
     * Determines whether to render the field with the component
     *
     * @var bool
     */
    protected bool $render = true;


    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function addToValues(): self
    {
        $this->addToValues = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function valueOnly(): self
    {
        // Value only fields are not rendered as a component or within a panel
        $this->render = false;
        $this->component = null;
        $this->addToValues = true;
        $this->panel = '';

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function component(string $component): self
    {
        $this->component = $component;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function panel(string $panel): self
    {
        $this->panel = $panel;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function indexComponent(string $string): self
    {
        $this->components[self::CONTEXT_INDEX] = $string;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function detailComponent(string $string): self
    {
        $this->components[self::CONTEXT_DETAIL] = $string;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function createComponent(string $string): self
    {
        $this->components[self::CONTEXT_CREATE] = $string;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function editComponent(string $string): self
    {
        $this->components[self::CONTEXT_EDIT] = $string;

        return $this;
    }

    protected function componentForContext(string $context): string | null
    {
        return $this->components[$context] ?? $this->component;
    }

    protected function setDefaultRender()
    {
        $this->component = 'text-field'; // function_exists('config') ? config('flex-admin.render.default_component') : 'text-field';
        $this->panel = 'details'; // function_exists('config') ? config('flex-admin.render.default_panel') : 'details';
    }

    protected function setDefaultComponents()
    {
        // initialize context components to null
        $this->components = $this->components ?? collect(self::CONTEXTS)->mapWithKeys(fn ($context) => [$context => null])->all();
    }
}
