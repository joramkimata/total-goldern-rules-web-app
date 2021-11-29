<?php

namespace App\Http\Controllers;

use App\Events\NewReminderEvent;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    //
    public function index()
    {
        $users = \App\User::where('role_id', 2)->get();
        $att = [];
        foreach ($users as $u) {
            $au = \App\Quiztracker::where('year', date('Y'))->where('user_id', $u->id)->where('month', date('n'))->where('depart_id', $u->department->id)->count();
            if ($au == 0) {
                $att[] = $u;
            }
        }
        $nalist = count($att);
        return view('reminders.index', compact('nalist'));
    }

    private function getRecipients($quizId)
    {
        $allusers = \App\User::where('role_id', 2)->where('active', 1)->get();
        $attendes = \App\Quiztracker::where('quiz_id', $quizId)->get();

        $none_attendes = [];

        foreach ($allusers as $u) {
            foreach ($attendes as $a) {
                if($u->id != $a->user_id) {
                    $none_attendes[] = $u->email;
                }
            }
        }

        return $none_attendes;
    }

    public function save()
    {
        $subject = request('reminder_subject');
        $bod = request('reminder_body');
        $view = 'emails.reminder_mail';

        $quizId = request('quizId');

        $recipients = $this->getRecipients($quizId);

        $r = new \App\Reminder;
        $r->reminder_subject = $subject;
        $r->reminder_body = $bod;
        $r->reminder_recipients = implode(", ", $recipients);
        $r->save();

        event(new NewReminderEvent($view, $subject, $bod, $recipients));

        // Send Email
       // \App\HelperX::sendReminders($view, $subject, $bod, $recipients);

        return response()->json([
            "error" => true,
            "msg" => "Successfully added!"
        ]);
    }

    public function refresh()
    {
        return redirect()->back()->with('success', 'Successfully Updated!');
    }
}
