<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;

class projectController extends Controller
{
    public function index($id)
    {

        $lang = request()->header('Accept-Language') ?? 'en';
        $project = Project::query()->find($id);
        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' : 'Data Created',
            'success' => true,
            'project' => $project,
        ]);
    }
}
