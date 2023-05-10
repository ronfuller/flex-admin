<?php

namespace Psi\FlexAdmin\DataTransferObjects;

use Psi\FlexAdmin\Enums\ElementColor;
use Psi\FlexAdmin\Enums\FieldFormat;
use Psi\FlexAdmin\Enums\TextSize;
use Psi\FlexAdmin\Enums\TextWeight;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class FormFieldAttributesData extends Data
{
    public function __construct(
        public readonly string $label,
        public string $name,
        public readonly string|Optional $placeholder,
        public readonly string|Optional $validationMessage,
        public FieldFormat|Optional $format,
        public bool|Optional $required,
        public bool|Optional $hidden,
        public bool|Optional $disable,
        public string|Optional $prependIcon,
        public string|Optional $type,
        public array|Optional $sections,
        public bool|Optional $autofocus,
        public bool|Optional $autoUpload,
        public string|Optional $accept,
        public bool|Optional $autogrow,
        public string|Optional $labelPosition,
        public bool|Optional $leftLabel,
        public bool|Optional $multiple,
        public bool|Optional $useChips,
        public string|Optional $standout,
        public bool|Optional $outlined,
        public int|Optional $applicant_id,
        public array|Optional $signatureLabels,
        public string|Optional $validationError,
        public bool|Optional $conditional,
        public string|Optional $conditionField,
        public array|Optional $conditions,
        public string|array|Optional $conditionValue,
        public string|Optional $conditionOperator,
        public string|Optional $trueValue,
        public string|Optional $falseValue,
        public bool|Optional $readonly,
        public array|Optional $indicator,
        public bool|Optional $indicateChange,
        public array|Optional $statements,
        public bool|Optional $enableUpdate,
        public readonly ElementColor|Optional $labelColor,
        public readonly ElementColor|Optional $iconColor,
        public readonly ElementColor|Optional $inputTextColor,
        public readonly TextSize|Optional $inputTextSize,
        public readonly TextWeight|Optional $inputTextWeight,
        public readonly TextSize|Optional $labelTextSize,
        public readonly TextWeight|Optional $labelTextWeight,

        // Checkbox Array
        public bool|Optional $inline,
        public bool|Optional $twoCols,
        public int|Optional $colWidth,

        // Content Options
        public string|Optional $hint,
        public bool|Optional $hideHint,
        public bool|Optional $clearable,

        // Select Field Attributes
        public array|Optional $options,
        public bool|Optional $optionsDense,
        public string|Optional $optionLabel,
        public string|Optional $optionalValue,
        public string|Optional $searchUrl,
        public string|Optional $emitValue,
    ) {
        $this->init('format', FieldFormat::Text);
        $this->init('required', true);
        $this->init('hidden', false);
    }

    protected function init(string $property, mixed $value)
    {
        $this->{$property} = is_a($this->{$property}, Optional::class) ? $value : $this->{$property};
    }
}
