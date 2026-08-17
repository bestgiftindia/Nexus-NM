<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index(){
        return view('home');
    }

    function about(){
        return view('about');
    }

    function contact(){
        return view('contact');
    }

    function help(){
        return view('help');
    }

    function privacy(){
        return view('privacy');
    }

    function terms(){
        return view('terms');
    }

    function refund(){
        return view('refund');
    }
}
