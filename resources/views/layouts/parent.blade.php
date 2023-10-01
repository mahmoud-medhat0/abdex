<?php

switch (auth()->user()->rank_id){
    case'7':
    $p = 'layouts.company';
    break;
    default:
        $p = 'layouts.'.session()->get('parent');
        break;
}
?>
@extends($p)
