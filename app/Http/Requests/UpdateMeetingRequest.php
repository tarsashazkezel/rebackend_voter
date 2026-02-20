<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingRequest extends FormRequest
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
    public function rules()
    {
        return [
            'title' => 'string|max:255',
            'meeting_date' => 'date|after:now',
            'location' => 'string|max:255',
            'agenda_items' => 'array',
            'agenda_items.*.title' => 'string',
            'agenda_items.*.description' => 'nullable|string',
            'agenda_items.*.resolution_text' => '|string',
        ];
    }
    public function messages(){
        return [
            'meeting_date.' => 'A Közgyűlés idözép megadása kötelező.',
            'agenda_items.*.title.' => 'A Napirendi pont megadása Kötelezos.',
            'agenda_items.*.resolution_text.' => 'A Napirendi pont megadása Kötelezos.',
            
            'title.string' => 'A Közgyűlés megadása szöveg kell legyen.',
            'location.string' => 'A Közgyűlés helye megadása szöveg kell legyen.',
            'agenda_items.*.title.string' => 'A Napirendi pont megadása szöveg kell legyen.',
            'agenda_items.*.resolution_text.string' => 'A Napirendi pont megadása szöveg kell legyen.',

            'title.max' => 'A Közgyűlés megadása maximum 255 karakter lehet.',
            'location.max' => 'A Közgyűlés helye megadása maximum 255 karakter lehet.',

            'meeting_date.date' => 'A Közgyűlés dátuma megadása dátum kell legyen.',

            'meeting_date.after' => 'A Közgyűlés dátuma nem lehet korábbi időpont a mostani napnál.',

            'agenda_items.array' => 'A Napirendi pontok tömbben kell, hogy átjöjjenek.',
            ];
    }
}
