<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\User;
use App\Services\ExportCsvFile;
use App\Services\MakeCommentCsvFile;
use App\Services\Messages\SendMessageAboutNewComment;
use Auth;
use DateTime;
use DateTimeInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    protected $exportCsv;
    protected $commentCsv;
    protected $newCommentMessage;

    public function __construct(ExportCsvFile $exportCsv, MakeCommentCsvFile $commentCsv, SendMessageAboutNewComment $newCommentMessage)
    {
        $this->exportCsv = $exportCsv;
        $this->commentCsv = $commentCsv;
        $this->newCommentMessage = $newCommentMessage;
    }

    public function index(int $id)
    {
        return Comment::with('users')->where('report_id', $id)->get();
    }

    public function store(CreateCommentRequest $request, int $conferenceId, int $reportId)
    {
        $this->sendMessage($reportId);
        $data = $request->validated();
        $data['report_id'] = $reportId;
        $data['user_id'] = Auth::user()->id;
        $data['conference_id'] = $conferenceId;
        Comment::create($data);
    }

    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function IsInRange(DateTime $startDate, DateTime $endDate)
    {
        return $startDate <= $endDate;
    }

    public function update(CreateCommentRequest $request, int $conferenceId, int $reportId, int $commentId)
    {
        $rep = Comment::findOrFail($commentId);
        $today = new Datetime('now - 10 minute');
        $today->format('Y-m-d H:i:s');
        $comDate = new Datetime($this->serializeDate($rep->updated_at));
        $today = new Datetime($this->serializeDate($today));
        if ($rep->user_id === Auth::user()->id && $this->IsInRange($today, $comDate) === true) {
            Comment::whereId($commentId)->update($request->validated());
        }
    }

    public function exportCsv(Request $request, int $id)
    {
        $this->exportCsv->export('comment', $id);
    }

    public function downloadCsv(int $id)
    {
        return $this->commentCsv->sendFile($id);
    }

    private function sendMessage(int $id)
    {
        $this->newCommentMessage->sendMessage(0, $id, 0);
    }
}
