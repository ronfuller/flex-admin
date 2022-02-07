<?php

namespace Psi\FlexAdmin\Fields;

trait FieldDisplay
{
    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function indexOnly(): self
    {
        $this->display = [self::CONTEXT_INDEX => true];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function detailOnly(): self
    {
        $this->display = [self::CONTEXT_DETAIL => true];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function editOnly(): self
    {
        $this->display = [self::CONTEXT_EDIT => true];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function createOnly(): self
    {
        $this->display = [self::CONTEXT_CREATE => true];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hideFromIndex(bool $condition = true): self
    {
        $this->display[self::CONTEXT_INDEX] = $condition ? false : true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hideFromDetail(bool $condition = true): self
    {
        $this->display[self::CONTEXT_DETAIL] = $condition ? false : true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hideFromCreate(): self
    {
        $this->display[self::CONTEXT_CREATE] = false;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hideFromEdit(): self
    {
        $this->display[self::CONTEXT_EDIT] = false;

        return $this;
    }

    protected function setDefaultDisplay()
    {
        $this->display = collect(self::CONTEXTS)->mapWithKeys(fn ($context) => [$context => true])->all();
    }

    private function displayContext(string $context): bool
    {
        return $this->display[$context] ?? false;
    }
}
