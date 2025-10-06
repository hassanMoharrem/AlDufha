<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WhyUsController extends Controller
{
    public function index(Request $request)
    {
        $lang = request()->header('Accept-Language') ?? 'en';

        $query = WhyUs::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('visible') && $request->visible !== 1) {
            $query->where('visible', $request->visible);
        }
        $data = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' : 'Data Created',
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store()
    {
        $lang = request()->header('Accept-Language') ?? 'en';
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string',
            'description' => 'required|string|min:5|max:1000',
            'visible' => 'nullable|boolean'
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $input = request()->all();


        $data = WhyUs::create($input);
        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' : 'Data Created',
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $whyUs = WhyUs::find($id);

        if (!$whyUs) {
            return response()->json(['message' => 'WhyUs not found'], 404);
        }

        $data = $whyUs->toArray();

        $checkboxFields = ['visible'];
        foreach ($checkboxFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = (bool)$data[$field];
            }
        }
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $lang = request()->header('Accept-Language') ?? 'en';

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string|min:5|max:1000',
            'visible' => 'nullable|boolean'
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $find = WhyUs::find($id);

        if (!$find) {
            return response()->json(['message' => 'WhyUs not found'], 404);
        }
        $data = $request->all();

        $find->update($data);
        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم تحديث بنجاح' : 'Data updated',
            'success' => true,
            'data' => $find,
        ]);
    }

    public function destroy($id)
    {
        $lang = request()->header('Accept-Language') ?? 'en';
        try {
            $find = WhyUs::find($id);
            $find->delete();
            return response()->json([
                'status' => 200,
                'message' => $lang == 'ar' ? 'تم الحذف بنجاح' : 'Data deleted',
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $lang == 'ar' ? 'خطأ' : 'Error',
                'success' => false,
            ]);
        }
    }
}
