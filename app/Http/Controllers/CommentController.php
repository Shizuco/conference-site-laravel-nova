<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Services\MakeCommentCsvFile;
use App\Jobs\CsvFile;
use App\Events\DownloadExportCsvFile;
use App\Jobs\SendMailWithQueue;
use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\User;
use App\Models\Report;
use App\Models\Conference;
use Illuminate\Http\Request;
use Auth;
use DateTime;
use DateTimeInterface;
use App\Services\Messages\SendMessageAboutNewComment;
use App\Services\ExportCsvFile;

class CommentController extends Controller
{
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
        if ($rep->user_id === Auth::user()->id && $this->IsInRange($today, $comDate) === false) {
            $data = $request->validated();
            $data['conference_id'] = $conferenceId;
            $data['report_id'] = $reportId;
            $data['user_id'] = Auth::user()->id;
            Comment::whereId($commentId)->update($data);
        }
    }

    public function exportCsv(Request $request, int $id)
    {
        ExportCsvFile::export('comment', $id);
    }

    public function downloadCsv(int $id)
    {
        return MakeCommentCsvFile::sendFile($id);
    }

    private function sendMessage(int $id)
    {
        SendMessageAboutNewComment::sendMessage(0, $id, 0);
    }
}
