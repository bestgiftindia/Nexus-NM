<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LpController extends Controller
{
    function nameReport(){
        $Mrp = 1999;
        $Price = 699;
        $pageinfo =[
            'mrp'=>$Mrp,
            'price'=>$Price,
            'discount' => $Mrp > 0 ? round((($Mrp - $Price) / $Mrp) * 100) : 0
        ];
        return view('landing-page.name-report.home',compact('pageinfo'));
    }
}
