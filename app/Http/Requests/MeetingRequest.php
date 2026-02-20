<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;


class MeetingRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'agenda_items' => 'required|array|min:1',
            'agenda_items.*.title' => 'required|string',
            'agenda_items.*.description' => 'nullable|string',
            'agenda_items.*.resolution_text' => 'required|string',
        ];
    }
    public function messages(){
        return [
            'title.required' => 'A Közgyűlés megadása kötelező.',
            'meeting_date.required' => 'A Közgyűlés idözép megadása kötelező.',
            'location.required' => 'A Közgyűlés helye megadása Kötelezos.',
            'agenda_items.required' => 'A Közgyűlés napirendi pontok megadása Kötelezos.',
            'agenda_items.*.title.required' => 'A Napirendi pont megadása Kötelezos.',
            'agenda_items.*.resolution_text.required' => 'A Napirendi pont megadása Kötelezos.',
            
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
    // public function failedValidation( Validator $validator ) {

    //     throw new HttpResponseException( response()->json([

    //         "success" => false,
    //         "message" => "Adatbeviteli hiba",
    //         "data" => $validator->errors()
    //     ]));
    // }
}
