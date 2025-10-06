<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $lang = request()->header('Accept-Language') ?? 'en';

        $query = Project::query();

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
            'sub_description' => 'required|string|min:5|max:1000',
            'description' => 'required|string|min:5|max:1000',
            'images.*' => 'nullable|image',
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

        $images = [];
        if (request()->hasFile('images')) {
            foreach (request()->file('images') as $image) {
                $images[] = $image->store('images', 'public');
            }
        }
        // إذا كانت الصور مصفوفة وليست فارغة، حولها إلى JSON
        $input['images'] = !empty($images) ? json_encode($images) : json_encode([]);
        $data = Project::create($input);
        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' : 'Data Created',
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $data = $project->toArray();
        // $data['images'] = json_decode($data['images']);


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
            'sub_description' => 'required|string|min:5|max:1000',
            'description' => 'required|string|min:5',
             'images.*' => 'nullable',
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

        $find = Project::find($id);
        $data = $request->except('image');
        $oldImages = is_array($find->images) ? $find->images : (json_decode($find->images, true) ?? []);
        $newImages = [];

        // إذا تم رفع صور جديدة
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $newImages[] = $image->store('images', 'public');
            }
            // دمج الصور القديمة مع الجديدة بدون تكرار
            $mergedImages = array_unique(array_merge($oldImages, $newImages));
            $data['images'] = json_encode($mergedImages);
        } elseif (is_array($request->images)) {
            // إذا تم إرسال الصور كمصفوفة من الواجهة (مثلاً بعد حذف أو ترتيب)
            $data['images'] = json_encode($request->images);
        } else {
            // إذا لم يتم إرسال صور جديدة أو مصفوفة صور، احتفظ بالصور القديمة كما هي
            $data['images'] = json_encode($oldImages);
        }

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
            $find = Project::find($id);
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
