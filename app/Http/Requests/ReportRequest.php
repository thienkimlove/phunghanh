<?php

namespace App\Http\Requests;

use App\Report;
use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'date' => 'required',
            'network_id' => 'required',
            'quantity' => 'required',
        ];

        return $rules;
    }

    public function after($validator)
    {

    }

    public function messages()
    {
        return [
            'date.required' => 'Vui lòng không để trống ngày',
            'network_id.required' => 'Vui lòng chọn network',
            'quantity.required' => 'Vui lòng không để trống số lượng',
        ];
    }

    public function store()
    {

        $date = $this->get('date');
        $network_id = $this->get('network_id');
        $quantity = $this->get('quantity');
        $phones = [];

        if ($this->get('phones')) {
            $phones = explode(',', $this->get('phones'));
        }

        $countByDate = Report::where('date', $date)->where('network_id', $network_id)->count();

        $needToInsert = intval($quantity) - $countByDate;

        if ($needToInsert > 0) {
            for ($i = 0; $i < $needToInsert; $i ++) {
                Report::create([
                    'network_id' => $network_id,
                    'date' => $date,
                    'phone' => (isset($phones[$i]) && $phones[$i]) ? $phones[$i] : uniqid(time().$i),
                ]);
            }
        }


        return $this;
    }

}
