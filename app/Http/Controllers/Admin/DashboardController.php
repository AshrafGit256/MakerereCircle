<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostModel;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'lecturer') {
            // Lecturer dashboard
            $data['myCourseUnits'] = $user->taughtCourseUnits;
            $data['totalAttendances'] = $user->taughtCourseUnits->sum(function($course) {
                return $course->attendances()->count();
            });
            $data['todayAttendances'] = $user->taughtCourseUnits->sum(function($course) {
                return $course->attendances()->whereDate('date', today())->count();
            });
            $data['header_title'] = "Lecturer Dashboard";
        } else {
            // Admin dashboard
            $data['TotalPosts'] = PostModel::getTotalPosts();
            $data['TotalTodayPosts'] = PostModel::getTotalTodayPosts();

            $data['TotalFound'] = PostModel::getTotalFound();
            $data['TotalTodayFound'] = PostModel::getTotalTodayFound();

            $data['TotalLost'] = PostModel::getTotalLost();
            $data['TotalTodayLost'] = PostModel::getTotalTodayLost();

            $data['TotalUsers'] = User::getTotalUser();
            $data['TotalTodayUsers'] = User::getTotalTodayUser();

            $data['getLatestPosts'] = PostModel::getLatestPosts();
            $data['header_title'] = "Admin Dashboard";
        }

        if(!empty($request->year))
        {
            $year = $request->year;
        }
        else
        {
            $year = date('Y');
        }

        $getTotalUserMonth = '';
        $getTotalPostMonth = '';
        $getTotalLostMonth = '';
        $getTotalFoundMonth = '';

        for($month = 1; $month <= 12; $month++)
        {
            $startDate = new \DateTime("$year-$month-01");
            $endDate = new \DateTime("$year-$month-01");
            $endDate->modify('last day of this month');

            $start_date = $startDate->format('Y-m-d');
            $end_date = $endDate->format('Y-m-d');

            // Fetch the total User count for the given month
            $user = User::getTotalUserMonth($start_date, $end_date);
            $getTotalUserMonth .= $user . ',';

            $post = PostModel::getTotalPostMonth($start_date, $end_date);
            $getTotalPostMonth .= $post . ',';

            $lost = PostModel::getTotalLostMonth($start_date, $end_date);
            $getTotalLostMonth .= $lost . ',';

            $found = PostModel::getTotalFoundMonth($start_date, $end_date);
            $getTotalFoundMonth .= $found . ',';

            // $totalAmount = $totalAmount + $order_payment;
        }

        $data['getTotalUserMonth'] = rtrim($getTotalUserMonth, ",");
        $data['getTotalPostMonth'] = rtrim($getTotalPostMonth, ",");
        $data['getTotalLostMonth'] = rtrim($getTotalLostMonth, ",");
        $data['getTotalFoundMonth'] = rtrim($getTotalFoundMonth, ",");

        $data['year'] = $year;


        $data['header_title'] = "Dashboard";
        
        // Pass the data to the view
        return view('admin.dashboard', $data);
    }
}
