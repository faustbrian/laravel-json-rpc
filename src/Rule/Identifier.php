<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Rule;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class Identifier implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (\is_numeric($value)) {
            return;
        }

        if (\is_string($value)) {
            return;
        }

        if ($value === null) {
            return;
        }

        $fail('The :attribute must be an integer, string or null.');
    }
}
