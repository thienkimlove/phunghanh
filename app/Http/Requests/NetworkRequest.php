<?php

namespace App\Http\Requests;

use App\Network;
use Illuminate\Foundation\Http\FormRequest;

class NetworkRequest extends FormRequest
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
        ];

        return $rules;
    }

    public function after($validator)
    {

    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng không để trống tên network',
        ];
    }

    public function store()
    {
        if (!$this->has('auto')) {
            $this->merge([
                'auto' => false,
            ]);
        }

        if (!$this->has('status')) {
            $this->merge([
                'status' => false,
            ]);
        }

        $network = Network::create($this->all());

        if ($network->is_sms_callback == 2 && !$network->cron_url) {
           $network->update([
               'cron_url' => 'http://media.seniorphp.net/report?network_id='.$network->id.'&start=#START&end=#END'
           ]);
        }

        return $this;
    }

    public function save($id)
    {

        if (!$this->has('auto')) {
            $this->merge([
                'auto' => false,
            ]);
        }

        if (!$this->has('status')) {
            $this->merge([
                'status' => false,
            ]);
        }

        $network = Network::findOrFail($id);

        $network->update($this->all());

        if ($network->is_sms_callback == 2 && !$network->cron_url) {
            $network->update([
                'cron_url' => 'http://media.seniorphp.net/report?network_id='.$network->id.'&start=#START&end=#END'
            ]);
        }


        return $this;
    }
}
