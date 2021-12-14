<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
			'title' => 'required|string|max:166',
			'content' => 'required|string|max:1000',
			'category' => 'required|numeric',
            'brand' => 'required|numeric',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
			'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'availability' => 'required|boolean'
		];
	}

	public function messages()
	{
		return [
			'title.required' => 'Title is required',
			'content.required' => 'Content is required',
			'category.required' => 'Category is required',
            'brand.required' => 'Brand is required',
			'image.image' => 'Image must be an image file',
			'image.mimes' => 'Image file must be .png .jpg .jpeg .gif',
			'image.max' => 'Maximum image size to upload is 2MB',
		];
	}

    protected function prepareForValidation()
	{
		$this->merge([
			'availability' => filter_var($this->availability, FILTER_VALIDATE_BOOLEAN)
		]);
	}

}
