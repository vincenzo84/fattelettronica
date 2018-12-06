<?php
namespace Syriaweb\Fattelettronica;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class FattelettronicaController extends Controller
{
    public function index($timezone)
    {
        echo Carbon::now($timezone)->toDateTimeString();
    }
}
