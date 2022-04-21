<?php

/**
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace Exhum4n\Components\Http\Requests;

use Exhum4n\Components\DataObjects\DataObject;
use Exhum4n\Components\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

/**
 * @property DataObject data
 */
abstract class FormRequest extends BaseFormRequest
{
    public DataObject $data;

    abstract public function rules(): array;

    public function authorize(): bool
    {
        return true;
    }

    public function all($keys = null): array
    {
        $data = parent::all();

        $params = $this->route()->parameters();
        if (empty($params)) {
            return $data;
        }

        foreach ($params as $key => $param) {
            if (is_numeric($param)) {
                $params[$key] = (int) $param;
            }
        }

        return array_merge($data, $params);
    }

    /**
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

    public function withValidator($validator): void
    {
        $validator->after(function (\Illuminate\Validation\Validator $validator) {
            $data = $validator->getData();

            $this->afterValidation($data);
        });
    }

    protected function afterValidation(array $data): void
    {
    }
}
