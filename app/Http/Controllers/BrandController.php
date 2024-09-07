<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class BrandController extends Controller
{

    public function BrandList():JsonResponse
    {
        $data= Brand::all();
        return ResponseHelper::Out('success',$data,200);
    }
}
