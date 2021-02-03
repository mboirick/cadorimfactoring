<?php

namespace App\Repositories\Backend;

use App\Models\Polls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PollsRepository
{
    public function create($params)
    {
        return Polls::create($params);;
    }

    public function getAll()
    {
        return Polls::select('id_sondage')
                        ->groupBy('id_sondage')
                        ->paginate(20);
    }

    public function getQuestionsByName($name)
    {
        return Polls::where('id_sondage', $name)
                        ->select('*')
                        ->orderBy('id')
                        ->pluck('text_question');
    }

    public function getByName($name)
    {
        return Polls::select('*')
                    ->where('id_sondage', $name)
                    ->orderBy('id')->get();
    }
}
