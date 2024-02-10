<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price' => [
                'numeric'
            ]
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'response' => 'error',
            'data' => null,
            'error' => $validator->errors()],
            406));
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $service = Service::find($this->route('service'));
            if (!$service) {
                $validator->errors()->add('service', 'Servicio no encontrado');
            }
        });
    }
}
