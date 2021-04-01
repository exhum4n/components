<?php

declare(strict_types=1);

namespace Exhum4n\Components\Http\Requests;

use Common\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    abstract public function rules(): array;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @param Validator $validator
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        $errorsMessages = $validator->errors()->getMessages();

        $errors = [];

        foreach ($errorsMessages as $key => $value) {
            $errors[$key] = $value[0];
        }

        throw new ValidationException(json_encode($errors));
    }
}
