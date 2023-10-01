<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class firebasecrud extends Controller
{
    private $database;

public function __construct()
{
    $this->database = \App\Services\FirebaseService::connect();
    return $this->middleware(['auth','admin']);
}

public function create($tt)
{
    $this->database
        ->getReference('message/' . $tt)
        ->set([
            'message' =>$tt,
            'title'=>$tt,
            'uid'=>'cN189jjprNWtmbErGV9L1Pj4kLQ2'
        ]);

    return response()->json('blog has been created');
}
public function index()
{
    return response()->json($this->database->getReference()->getValue());
}


}
