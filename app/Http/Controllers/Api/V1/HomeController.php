<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Admin\Banner;
use App\Models\Admin\BannerText;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use HttpResponses;

    public function home()
    {
        $banners = Banner::all();
        $bannerText = BannerText::latest()->first();
        $user = new PlayerResource(Auth::user());

        return $this->success([
            'banners' => $banners,
            'bannerText' => $bannerText,
            'user' => $user,
        ]);
    }
}
