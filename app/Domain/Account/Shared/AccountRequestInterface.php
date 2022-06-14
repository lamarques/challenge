<?php

namespace App\Domain\Account\Shared;

use Illuminate\Foundation\Http\FormRequest;

interface AccountRequestInterface
{
    public function authorize();
    public function rules();
}
