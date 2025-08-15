<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FieldRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $isCreate = $this->isMethod('post'); // true saat create

        return [
            'img'           => $isCreate ? 'required|file|mimes:jpg,png' : 'nullable|file|mimes:jpg,png',
            'detail.*'      => 'nullable|file|mimes:jpg,png',
            'field_type_id' => 'required|exists:field_types,id',
            'name'          => 'required|string|max:100',
            'price'         => 'required|numeric|min:0',
            'width'         => 'required|numeric|min:0',
            'height'        => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'img.required' => 'Gambar sampul lapangan wajib diunggah.',
            'img.file' => 'Gambar sampul harus berupa file.',
            'img.mimes' => 'Format gambar sampul harus jpg atau png.',
            'detail.*.file' => 'Gambar detail harus berupa file.',
            'detail.*.mimes' => 'Format gambar detail harus jpg atau png.',
            'field_type_id.required' => 'Jenis lapangan wajib dipilih.',
            'field_type_id.exists' => 'Jenis lapangan tidak valid.',
            'name.required' => 'Nama lapangan wajib diisi.',
            'price.required' => 'Harga sewa wajib diisi.',
            'price.numeric' => 'Harga sewa harus berupa angka.',
            'width.required' => 'Panjang lapangan wajib diisi.',
            'height.required' => 'Lebar lapangan wajib diisi.',
        ];
    }
}
