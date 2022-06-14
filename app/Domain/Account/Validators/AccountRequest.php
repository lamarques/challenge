<?php

namespace App\Domain\Account\Validators;

use App\Domain\Account\Shared\AccountRequestInterface;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest implements AccountRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payee' => 'required',
            'value' => 'required|numeric|min:0|not_in:0',
        ];
    }
}
