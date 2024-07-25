<?php

namespace App\Http\Controllers;

use App\Models\Grid;
use Illuminate\Http\Request;
use App\Providers\Modules;

class Grids extends Controller
{
    public function __construct(){
        parent::__construct(Grid::class, Modules::M_GRIDS);
    }
}
