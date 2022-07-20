<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\User;
use Auth, DateTime, DateTimeZone, DateTimeInterface;

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

    function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function IsInRange(DateTime $startDate, DateTime $endDate)
    {
        return $startDate <= $endDate;
    }

    public function update(CreateCommentRequest $request, int $conference_id, int $report_id, int $comment_id)
    {
        $rep = Comment::findOrFail($comment_id);
        $today = new Datetime('now - 10 minute');
        $today->format('Y-m-d H:i:s');
        $comDate = new Datetime($this->serializeDate($rep->updated_at));
        $today = new Datetime($this->serializeDate($today));
        //return ($this->IsInRange($today, $comDate));
        if ($rep->user_id === Auth::user()->id && $this->IsInRange($today, $comDate) == 1) {
            $data = $request->validated();
            $data['conference_id'] = $conference_id;
            $data['report_id'] = $report_id;
            $data['user_id'] = Auth::user()->id;
            Comment::whereId($comment_id)->update($data);
        }
    }
}
