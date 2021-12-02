<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        return view('reports.index');
    }

    private function getMonth($index) {
        $months = ["January", "February", "March", "April",
            "May", "June", "July", "August", "September",
            "October", "November", "December"];

        return $months[$index - 1];
    }

    public function viewStaff() {
        $dept = request('depart');
        $staff = request('staff');
        $month = request('month');
        $year = request('year');

        if($staff == "a") {
            if($dept == "all") {
                $allAttempts =  \App\Quiztracker::where('year', $year)->where('month', $month)->get();

                return view('reports.attempts', compact('allAttempts'));
            }else {
                $allAttempts =  \App\Quiztracker::where('year', $year)->where('month', $month)->where('depart_id', $dept)->get();
                return view('reports.attempts', compact('allAttempts'));
            }
        }else {
            if($dept == "all") {
                $allUsers = \App\User::where('role_id', 2)->get();

                return view('reports.none_attempts', compact('allUsers'));
            }else {
                $users = \App\User::where('role_id', 2)->get();
                $att = [];
                foreach ($users as $u) {
                    if($u->department->id == $dept) {
                        $au = \App\Quiztracker::where('year', $year)->where('user_id', $u->id)->where('month', $month)->where('depart_id', $dept)->count();
                        if($au == 0) {
                            $att[] = $u;
                        }
                    }
                }
                $allUsers = $att;
                return view('reports.none_attempts', compact('allUsers'));
            }
        }
    }


    public function fetchReport() {
        $dept = request('deprtId');
        $staff = request('staff');
        $month = request('month');
        $year = request('year');

        $m = $this->getMonth($month);

        $allUsers = 0;
        $allAttempts = 0;
        $allNoneAttempts = 0;

        if($dept == "all") {
            $allUsers = \App\User::where('role_id', 2)->count();
            $allAttempts =  \App\Quiztracker::where('year', $year)->where('month', $month)->count();
            $allNoneAttempts = $allUsers - $allAttempts;
        }else {
            $users = \App\User::where('role_id', 2)->get();
            $att = 0;
            foreach ($users as $u) {
                if($u->department->id == $dept) {
                    $att = $att + 1;
                }
            }
            $allUsers = $att;
            $allAttempts =  \App\Quiztracker::where('year', $year)->where('month', $month)->where('depart_id', $dept)->count();
            $allNoneAttempts = $allUsers - $allAttempts;
        }




        return view('reports.view', compact('dept', 'staff', 'month', 'year', 'm', 'allNoneAttempts', 'allAttempts'));
    }
}
