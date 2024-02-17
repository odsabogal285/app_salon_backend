<?php

namespace App\Http\Requests\Appointment;

use App\Models\Appointment;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditAppointmentRequest extends FormRequest
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

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($validator->errors()->get('appointment')){
            throw new HttpResponseException(response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $validator->errors()->get('appointment')[0]],
                404));
        }
        throw new HttpResponseException(response()->json([
            'response' => 'error',
            'data' => null,
            'error' => $validator->errors()->all()],
            406));
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $appointment = Appointment::find($this->route('appointment'));
            if (!$appointment) {
                $validator->errors()->add('appointment', 'Cita no encontrada');
            }
        });
    }
}
