<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Service\CountryService;
use App\Models\User;
use App\Models\Report;
use App\Models\Comment;
use App\Http\Requests\CreateCommentRequest;
use Auth, Redirect, Validator;

class CommentController extends Controller
{
    public function index(int $id)
    {
        return response()->json(Comment::All()->where('report_id', $id));
    }

    public function store(CreateCommentRequest $request, int $conference_id, int $report_id)
    {
        $data = $request->validated();
        $data['report_id'] = $report_id;
        $data['user_id'] = Auth::user()->id;
        $data['conference_id'] = $conference_id;
        Comment::create($data);
    }

    public function update(CreateCommentRequest $request, int $report_id, int $comment_id)
    {   
        $data = $request->validated();
        $data['report_id'] = $report_id;
        $data['user_id'] = Auth::user()->id;
        Comment::whereId($comment_id)->update($data);
    }
}