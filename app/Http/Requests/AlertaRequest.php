<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlertaRequest extends FormRequest
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
            'mensagem' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'string', 'in:info,warning,error,success'],
            'status' => ['required', 'string', 'in:pending,read,archived'],
        ];
    }

    public function messages(): array
    {
        return [
            'mensagem.required' => 'A mensagem é obrigatória',
            'mensagem.max' => 'A mensagem não pode ter mais que 255 caracteres',
            'tipo.required' => 'O tipo é obrigatório',
            'tipo.in' => 'O tipo deve ser: info, warning, error ou success',
            'status.required' => 'O status é obrigatório',
            'status.in' => 'O status deve ser: pending, read ou archived',
        ];
    }
}
