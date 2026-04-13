<?php

namespace App\Rules;

use App\Models\Currency;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CurrencyCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = Currency::all();

        $code = $data->map(function ($data) {
            return $data->code;
        });



        $validCurrencies = $code->toArray();

        if (!in_array($value, $validCurrencies)) {
            $fail('The selected currency code is invalid.');
        }
    }
}
