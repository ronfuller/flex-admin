<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Psi\FlexAdmin\Enums\SectionActionType;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionActionData extends Data
{
    public function __construct(
        #[DataCollectionOf(SectionActionControlData::class)]
        public ?DataCollection $controls = null,
        public readonly string $component = 'actions',
        public readonly bool $hide = false,
        public array $defaults = ['cancel', 'submit']
    ) {
        $this->defaults = $this->hide ? [] : $this->defaults;

        if (count($this->defaults)) {
            $defaultControls = collect($this->defaults)->map(
                function ($control) {
                    $actionType = SectionActionType::from($control);

                    return SectionActionControlData::from([
                        'type' => $actionType,
                        'attr' => [
                            'label' => $actionType->label(),
                            'icon' => $actionType->icon(),
                            'color' => $actionType->color(),
                        ],
                        'hideLabelOnMobile' => $actionType->hideLabelOnMobile(),
                    ])->toArray();
                }
            )->all();
            $this->controls = $this->controls ?? SectionActionControlData::collection($defaultControls);
        }
    }
}
