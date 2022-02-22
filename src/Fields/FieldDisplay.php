<?php

namespace Psi\FlexAdmin\Fields;

use Psi\FlexAdmin\Fields\Enums\DisplayContext;

trait FieldDisplay
{

    /**
     * Defines the contexts in which the resource field is displayed
     *
     * @var array
     */
    public $display;

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function indexOnly(): self
    {
        $this->display = [DisplayContext::INDEX->value => true];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function detailOnly(): self
    {
        $this->display = [DisplayContext::DETAIL->value => true];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function editOnly(): self
    {
        $this->display = [DisplayContext::EDIT->value => true];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function createOnly(): self
    {
        $this->display = [DisplayContext::CREATE->value => true];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hideFromIndex(bool $condition = true): self
    {
        $this->display[DisplayContext::INDEX->value] = $condition ? false : true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hideFromDetail(bool $condition = true): self
    {
        $this->display[DisplayContext::DETAIL->value] = $condition ? false : true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hideFromCreate(): self
    {
        $this->display[DisplayContext::CREATE->value] = false;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hideFromEdit(): self
    {
        $this->display[DisplayContext::EDIT->value] = false;

        return $this;
    }

    protected function setDefaultDisplay()
    {
        $this->display = collect(DisplayContext::values())->mapWithKeys(fn ($context) => [$context => true])->all();
    }

    private function displayContext(string $context): bool
    {
        return $this->display[$context] ?? false;
    }
}
