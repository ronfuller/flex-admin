<?php
namespace Psi\FlexAdmin\DataTransferObjects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
// use Spatie\LaravelData\DataCollection;
// use Spatie\LaravelData\Attributes\DataCollectionOf;
// use Spatie\LaravelData\Optional;
// use Spatie\LaravelData\Attributes\WithTransformer;
// use Spatie\LaravelData\Attributes\WithCast;

use Spatie\LaravelData\Data;

/**
 * Spatie Data Object Documentation
 * https://spatie.be/docs/laravel-data/v2/as-a-data-transfer-object/creating-a-data-object
 */
final class SectionAttributesData extends Data
{
    public function __construct(
        public readonly ?bool $canFake = true,
    ) {
    }

    // public static function fromRequest(Request $request): self
    // {
    //     return self::from([
    //         'id' => $request->id,
    //     ]);
    // }

    // public static function fromModel(Model $model): self
    // {
    //     return self::from([
    //         'id' => $model->id,
    //     ]);
    // }

    // public static function withValidator(Validator $validator): void
    // {
    //     $validator->setRules(self::rules());
    // }

    // public static function rules(): array
    // {
    //     return [
    //         'id' => ['required'],
    //     ];
    // }
}
