<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreRequest extends Request {

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
			'name' => 'required|max:255|unique:stores,name,' . $this->segment(2),
			'info' => 'required',
			'slug' => 'required|max:255|unique:stores,slug,' . $this->segment(2),
		];
	}

	/**
	 * 自訂的錯誤訊息
	 * @return array
	 */
	public function messages()
	{
		$messages = [
			'name.required' => '店名 為必填.',
			'name.unique' => '此店名已使用. 請更換一個',

			'info.required' => '簡介 為必填',

			'slug.required' => '縮寫 為必填.',
			'slug.unique' => '此縮寫已使用. 請更換一個',
		];

		return $messages;
	}

}
