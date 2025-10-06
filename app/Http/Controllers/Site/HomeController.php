<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Impact;
use App\Models\Project;
use App\Models\Slider;
use App\Models\WhyUs;

class HomeController extends Controller
{
    public function index()
    {
        $lang = request()->header('Accept-Language') ?? 'en';
        $slider = Slider::query()->limit(10)->get();
        $about = About::query()->limit(7)->get();
        $whyUs = WhyUs::query()->limit(3)->get();
        $impact = Impact::query()->limit(5)->get();
        $projects = Project::query()->limit(3)->get();
       
        return response()->json([
            'status' => 200,
            'message' => $lang == 'ar' ? 'تم إنشاء البيانات بنجاح' : 'Data Created',
            'success' => true,
            'slider' => $slider,
            'about' => $about,
            'whyUs' => $whyUs,
            'impact' => $impact,
            'projects' => $projects,
        ]);
    }
}
