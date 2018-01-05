<?php

namespace App\Http\Requests;

use App\Connection;
use Illuminate\Foundation\Http\FormRequest;

class ConnectionRequest extends FormRequest
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

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator) {
            // Call the after method of the FormRequest (see below)
            $this->after($validator);
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'callback' => 'required|max:255',
        ];

        return $rules;
    }

    public function after($validator)
    {

    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng không để trống tên connection',
            'callback.required' => 'Vui lòng không để trống callback URL',
        ];
    }

    public function store()
    {

        $data = $this->all();

        Connection::create($data);

        return $this;
    }

    public function save($id)
    {

        $connection = Connection::find($id);

        $data = $this->all();

        $connection->update($data);

        return $this;
    }
}
