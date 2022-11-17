<?php
namespace Psi\FlexAdmin\DataTransferObjects;

use Spatie\LaravelData\Data;

// use Spatie\LaravelData\DataCollection;
// use Spatie\LaravelData\Attributes\DataCollectionOf;
// use Spatie\LaravelData\Optional;
// use Spatie\LaravelData\Attributes\WithTransformer;
// use Spatie\LaravelData\Attributes\WithCast;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SignatureLabelData extends Data
{
    public function __construct(
        public readonly ?string $title = '',
        public readonly ?string $placeholder = '',
        public readonly ?string $type = '',
        public readonly ?string $draw = '',
        public readonly ?string $drawHint = '',
        public readonly ?string $clearHint = '',
        public readonly ?string $typeHint = '',
        public readonly ?string $cancel = '',
        public readonly ?string $done = ''
    ) {
    }

    public static function withLocale()
    {
        return new self(
            title: __('labels.signature-title'),
            placeholder: __('labels.signature-placeholder'),
            draw: __('labels.signature-draw'),
            type: __('labels.signature-type'),
            drawHint: __('labels.signature-draw-hint'),
            clearHint: __('labels.signature-clear-hint'),
            typeHint: __('labels.signature-type-hint'),
            cancel: __('labels.signature-cancel'),
            done: __('labels.signature-done')
        );
    }
}
