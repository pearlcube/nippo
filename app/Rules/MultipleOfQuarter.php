<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MultipleOfQuarter implements Rule
{
    public function passes($attribute, $value)
    {
        // 0.25の倍数を判定
        return fmod($value, 0.25) === 0.0 && $value <= 99.99;
    }

    public function message()
    {
        return ':attribute は 0.25 の倍数で、小数点以下2桁以内の値を指定してください（最大99.99）。';
    }
}
