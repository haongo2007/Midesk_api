<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
        ];
        switch($this->method())
        {
            case 'POST':
            {
                return [
                    'title' => 'required|max:255|min:3',
                    'content' => 'required'
                ];
            }
            case 'PUT':
            {
                return [
                    'title' => 'required|max:255|min:3',
                ];
            }
            default:break;
        }
    }
}
