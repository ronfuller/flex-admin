<?php

namespace Psi\FlexAdmin\Enums;

/**
 * Documentation on Enums
 * https://stitcher.io/blog/php-enums
 */
enum FieldComponent: string
{
    case Text = 'text-field';
    case Select = 'select-field';
    case Hidden = 'hidden-field';
    case Switch = 'switch-field';
    case Checkbox = 'checkbox-field';
    case FileUpload = 'file-upload-field';
    case CheckboxArray = 'checkbox-array-field';
    case CheckboxListArray = 'checkbox-list-array-field';
    case Radio = 'radio-field';
    case Editor = 'editor-field';
    case Signature = 'signature-field';
    case PopupSelect = 'popup-select-field';
    case CustomSelect = 'custom-select-field';

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
