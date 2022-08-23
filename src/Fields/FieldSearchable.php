<?php

namespace Psi\FlexAdmin\Fields;

trait FieldSearchable
{
    public static $SEARCH_TYPES = ['full', 'exact', 'partial'];

    /**
     * Search type - exact, full, partial
     *
     * @var string
     */
    protected string $searchType;

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function searchable(string $type = null): self
    {
        if ($type && ! in_array($type, self::$SEARCH_TYPES)) {
            throw new \Exception('Invalid search type, must be one of '.implode(',', self::$SEARCH_TYPES));
        }

        $this->searchType = $type ?? $this->searchType;
        $this->meta['searchable'] = true;

        return $this;
    }

    protected function setDefaultSearchType(): void
    {
        // TODO: Pull this from configuration
        $this->searchType = 'full';
    }
}
