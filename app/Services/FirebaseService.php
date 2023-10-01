<?php

namespace App\Services;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;

class FirebaseService
{
    public static function connect()
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path('/storage/firebase/mar-express-3b0a1-e2a660229300.json'))
            ->withDatabaseUri('https://mar-express-3b0a1-default-rtdb.firebaseio.com/');

        return $firebase->createDatabase();
    }

}
