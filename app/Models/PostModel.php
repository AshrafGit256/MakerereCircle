<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostModel extends Model
{
    use HasFactory;

    protected $table = 'posts';


    public static function getSingle(int $id)
    {
        return self::find($id);
    }


    static public function getTotalPosts()
    {
        return self::select('id')
            ->where('is_delete', '=', 0)
            ->count();
    }

    static public function getTotalTodayPosts()
    {
        return self::select('id')
            ->where('is_delete', '=', 0)
            ->whereDate('created_at', '=', date('Y-m-d'))
            ->count();
    }

    static public function getTotalFound()
    {
        return self::select('id')
            ->where('found', '=', 1)
            ->where('lost', '=', 0)
            ->where('is_delete', '=', 0)
            ->count();
    }

    static public function getTotalTodayFound()
    {
        return self::select('id')
            ->where('found', '=', 1)
            ->where('lost', '=', 0)
            ->where('is_delete', '=', 0)
            ->whereDate('created_at', '=', date('Y-m-d'))
            ->count();
    }

    static public function getTotalLost()
    {
        return self::select('id')
            ->where('found', '=', 0)
            ->where('lost', '=', 1)
            ->where('is_delete', '=', 0)
            ->count();
    }

    static public function getTotalTodayLost()
    {
        return self::select('id')
            ->where('found', '=', 0)
            ->where('lost', '=', 1)
            ->where('is_delete', '=', 0)
            ->whereDate('created_at', '=', date('Y-m-d'))
            ->count();
    }

    static public function getTotalUser($user_id)
    {
        return self::select('id')
            ->where('user_id', '=', $user_id)
            ->where('is_admin', '=', 0)
            ->where('is_delete', '=', 0)
            ->count();
    }

    static public function getTotalTodayUser($user_id)
    {
        return self::select('id')
            ->where('user_id', '=', $user_id)
            ->where('is_admin', '=', 0)
            ->where('is_delete', '=', 0)
            ->whereDate('created_at', '=', date('Y-m-d'))
            ->count();
    }
    /**
     * Get the total number of non-deleted posts between two dates.
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public static function getTotalPostMonth(string $startDate, string $endDate): int
    {
        return self::where('is_delete', 0)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->count();
    }

    /**
     * Get the total number of lost posts (lost = 1, found = 0) between two dates.
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public static function getTotalLostMonth(string $startDate, string $endDate): int
    {
        return self::where('lost', 1)
            ->where('found', 0)
            ->where('is_delete', 0)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->count();
    }

    /**
     * Get the total number of found posts (lost = 0, found = 1) between two dates.
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public static function getTotalFoundMonth(string $startDate, string $endDate): int
    {
        return self::where('lost', 0)
            ->where('found', 1)
            ->where('is_delete', 0)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->count();
    }

    public static function getLatestPosts()
    {
        return PostModel::select('posts.*')
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
    }


    public static function getRecord()
    {
        return self::select('posts.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.is_delete', 0)
            ->orderBy('posts.id', 'desc')
            ->get();
    }

    /**
     * Get the full URL of the post's image if it exists.
     *
     * @return string
     */
    public function getImage()
    {
        if (!empty($this->image_name)) {
            return asset('storage/media/' . $this->image_name);
        }
        return asset('images/default.jpg');
    }
}
