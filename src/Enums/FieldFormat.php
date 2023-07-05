<?php

namespace Psi\FlexAdmin\Enums;

/**
 * Documentation on Enums
 * https://stitcher.io/blog/php-enums
 */
enum FieldFormat: string
{
    case Text = 'text';
    case Numeric = 'numeric';
    case Phone = 'phone';
    case Date = 'date';
    case ShortDate = 'shortDate';
    case Email = 'email';
    case Password = 'password';
    case Card = 'card';
    case SSN = 'ssn';
    case Currency = 'currency';
    case Money = 'money';
    case PasswordConfirmation = 'password_confirmation';
    case Autocomplete = 'autocomplete';
    case Filter = 'filter';
    case Array = 'array';
    case Google = 'google';

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($case) => $case->value)->all();
    }
}
