<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FieldRequest;
use App\Models\FieldType;
use App\Models\BdmField;
use App\Models\BdmImage;
use Exception;
use Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FieldController extends Controller
{
    public function index()
    {
        $q = request()->q;
        $fields = BdmField::when($q, function ($query) use ($q) {
            return $query->search($q);
        })->orderByDesc('updated_at')->paginate(6)->withQueryString();

        return view('admin.master.field.index', compact('fields'));
    }

    public function create()
    {
        $fieldTypes = FieldType::get();
        return view('admin.master.field.create', compact('fieldTypes'));
    }

    public function store(Request $request)
    {
        try {
            $fieldRequest = new FieldRequest();

            $validator = Validator::make($request->all(), [
                'img' => 'required|file|mimes:png,jpg',
                'detail.*' => 'nullable|file|mimes:png,jpg',
                'field_type_id' => 'required|exists:field_types,id',
                'name' => 'required|string',
                'price' => 'required|numeric',
                'width' => 'required|numeric',
                'height' => 'required|numeric',
            ], $fieldRequest->messages());

            if ($validator->fails()) {
                $errors = Helpers::setErrors($validator->errors()->messages());
                return redirect()->back()->with('errors', $errors)->withInput();
            }

            $data = $validator->validated();
            $cover = $request->file('img');
            $details = $request->file('detail');

            $bdmField = BdmField::create([
                'field_type_id' => $data['field_type_id'],
                'name' => $data['name'],
                'price' => $data['price'],
                'width' => $data['width'],
                'height' => $data['height'],
            ]);

            if ($cover) {
                $bdmField->uploadCover($cover);
            }

            if (!empty($details)) {
                BdmImage::uploadDetailImg($details, $bdmField->id);
            }

            return redirect(route('admin.field.index'))->withSuccess('Data lapangan berhasil ditambahkan!');
        } catch (Exception $e) {
            return redirect()->back()->with('errors', $e->getMessage())->withInput();
        }
    }

    public function edit(BdmField $field)
    {
        $fieldTypes = FieldType::get();
        return view('admin.master.field.edit', compact('fieldTypes', 'field'));
    }

    public function update(BdmField $field, Request $request)
    {
        try {
            $fieldRequest = new FieldRequest();

            $validator = Validator::make($request->all(), [
                'img' => 'nullable|file|mimes:png,jpg',
                'detail.*' => 'nullable|file|mimes:png,jpg',
                'field_type_id' => 'required|exists:field_types,id',
                'name' => 'required|string',
                'price' => 'required|numeric',
                'width' => 'required|numeric',
                'height' => 'required|numeric',
            ], $fieldRequest->messages());

            if ($validator->fails()) {
                $errors = Helpers::setErrors($validator->errors()->messages());
                return redirect()->back()->with('errors', $errors)->withInput();
            }

            $data = $validator->validated();
            $cover = $request->file('img');
            $details = $request->file('detail');

            $field->update([
                'field_type_id' => $data['field_type_id'],
                'name' => $data['name'],
                'price' => $data['price'],
                'width' => $data['width'],
                'height' => $data['height'],
            ]);

            if ($cover) {
                $field->uploadCover($cover);
            }

            if (!empty($details)) {
                // Hapus gambar lama
                $images = BdmImage::where('bdm_field_id', $field->id)->get();
                foreach ($images as $img) {
                    $path = str_replace("storage", "public", $img->img);
                    if (Storage::exists($path)) {
                        Storage::delete($path);
                    }
                }
                BdmImage::where('bdm_field_id', $field->id)->delete();

                // Upload gambar baru
                BdmImage::uploadDetailImg($details, $field->id);
            }

            return redirect()->back()->withSuccess('Data lapangan berhasil diubah!');
        } catch (Exception $e) {
            return redirect()->back()->with('errors', $e->getMessage())->withInput();
        }
    }

    public function destroy(BdmField $field)
    {
        try {
            $field->delete();
            return response()->json(['success' => true, 'message' => 'Lapangan berhasil dihapus!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function show(BdmField $field)
    {
        $images = $field->bdm_images;
        $imageExist = $images->count() > 0;

        return view('admin.master.field.show', compact('field', 'images', 'imageExist'));
    }
}
