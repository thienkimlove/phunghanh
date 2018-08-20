<?php

namespace App\Http\Requests;

use App\Connection;
use App\Network;
use App\Site;
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

        if (!$this->has('must_set_header')) {
            $this->merge([
                'must_set_header' => false,
            ]);
        }

        if (!$this->has('number_redirect')) {
            $this->merge([
                'number_redirect' => 1,
            ]);
        }

        if (!$this->has('number_click_to_add_conversion')) {
            $this->merge([
                'number_click_to_add_conversion' => 10000,
            ]);
        }

        $this->merge([
            'user_id' => auth()->user()->id,
        ]);

        if ($this->has('connection_id')) {
            $connection = Connection::find($this->get('connection_id'));
            $this->merge([
                'callback_url' => $connection->callback,
                'map_params' => $connection->map_params,
                'extend_params' => $connection->extend_params,
                'callback_allow_ip' => '',
            ]);
        }

        if ($this->has('click_url')) {
            $generate_json = [];
            for ($i = 0; $i < 3; $i++) {
                $temp = [
                    'click_url' => isset($this->get('click_url')[$i]) ? $this->get('click_url')[$i] : null,
                    'allow_ip' => isset($this->get('allow_ip')[$i]) ? $this->get('allow_ip')[$i] : null,
                    'number_click_per_minute' => isset($this->get('number_click_per_minute')[$i]) ? $this->get('number_click_per_minute')[$i] : null,
                ];

                $generate_json[] = $temp;
            }

            $click_data = json_encode($generate_json, true);

            $this->merge([
                'click_url' => $click_data
            ]);
        }


        $network = Network::create($this->all());

        if ($network->is_sms_callback == 2) {
            $network->update([
                'cron_url' => 'http://media.seniorphp.net/report?network_id='.$network->id.'&start=#START&end=#END'
            ]);
        }

        Site::fillLinkIdToNetwork($network);

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

        if (!$this->has('must_set_header')) {
            $this->merge([
                'must_set_header' => false,
            ]);
        }

        if (!$this->has('number_redirect')) {
            $this->merge([
                'number_redirect' => 1,
            ]);
        }

        if (!$this->has('number_click_to_add_conversion')) {
            $this->merge([
                'number_click_to_add_conversion' => 10000,
            ]);
        }

        if ($this->has('click_url')) {
            $generate_json = [];
            for ($i = 0; $i < 3; $i++) {
                $temp = [
                    'click_url' => isset($this->get('click_url')[$i]) ? $this->get('click_url')[$i] : null,
                    'allow_ip' => isset($this->get('allow_ip')[$i]) ? $this->get('allow_ip')[$i] : null,
                    'number_click_per_minute' => isset($this->get('number_click_per_minute')[$i]) ? $this->get('number_click_per_minute')[$i] : null,
                ];

                $generate_json[] = $temp;
            }

            $click_data = json_encode($generate_json, true);

            $this->merge([
                'click_url' => $click_data
            ]);
        }


        $network = Network::findOrFail($id);

        $network->update($this->all());

        if ($network->is_sms_callback == 2 && !$network->cron_url) {
            $network->update([
                'cron_url' => 'http://media.seniorphp.net/report?network_id='.$network->id.'&start=#START&end=#END'
            ]);
        }

        Site::fillLinkIdToNetwork($network);



        return $this;
    }
}
