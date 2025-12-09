<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Overtrue\LaravelLike\Traits\Liker;
use Overtrue\LaravelFavorite\Traits\Favoriter;
use Overtrue\LaravelFollow\Traits\Followable;
use Overtrue\LaravelFollow\Traits\Follower;
use Illuminate\Support\Facades\Request;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Liker;
    use Favoriter;
    use Follower;
    use Followable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'title',
        'bio',
        'course',
        'education_level',
        'employment_status',
        'location',
        'skills',
        'schools',
        'talents',
        'image_name',
        'role',
        'gender',
        'date_of_birth',
        'year_of_study',
        'semester',
        'looking_for',
        'interests',
        'region',
        'phone',
        'social_links',
        'is_online',
        'last_seen',
        'connection_count',
        'is_verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'social_links' => 'array',
        'is_online' => 'boolean',
        'last_seen' => 'datetime',
        'date_of_birth' => 'date',
        'is_verified' => 'boolean',
    ];

    function posts(): HasMany
    {

        return $this->hasMany(Post::class);
    }


    function comments(): HasMany
    {

        return $this->hasMany(Comment::class);
    }

    function conversations(): HasMany
    {

        return $this->hasMany(Conversation::class, 'sender_id')->orWhere('receiver_id', $this->id);
    }

    /**
     * The channels the user receives notification broadcasts on.
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.' . $this->id;
    }


    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Gamification relationships
    public function points()
    {
        return $this->hasMany(UserPoint::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('earned_at');
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function streak()
    {
        return $this->hasOne(UserStreak::class);
    }

    // Gamification helper methods
    public function getTotalPoints()
    {
        return UserPoint::getUserTotalPoints($this->id);
    }

    public function awardPoints($points, $actionType, $description, $metadata = null)
    {
        $pointRecord = UserPoint::awardPoints($this->id, $points, $actionType, $description, $metadata);

        // Update streak
        $streak = UserStreak::getOrCreateForUser($this->id);
        $streak->updateStreak();

        // Check for new badges
        $this->checkAndAwardBadges();

        return $pointRecord;
    }

    public function checkAndAwardBadges()
    {
        $badges = Badge::where('is_active', true)->get();

        foreach ($badges as $badge) {
            $badge->awardToUser($this);
        }
    }

    public function getCurrentStreak()
    {
        $streak = $this->streak;
        return $streak ? $streak->current_streak : 0;
    }

    public function getLongestStreak()
    {
        $streak = $this->streak;
        return $streak ? $streak->longest_streak : 0;
    }

    // Award daily login bonus
    public function awardDailyLoginBonus()
    {
        $today = now()->toDateString();
        $lastLoginBonus = $this->points()
            ->where('action_type', 'daily_login')
            ->whereDate('created_at', $today)
            ->first();

        if (!$lastLoginBonus) {
            // Award daily login points
            $this->awardPoints(5, 'daily_login', 'Daily login bonus');

            // Update streak
            $streak = UserStreak::getOrCreateForUser($this->id);
            $streak->updateStreak();

            return true;
        }

        return false;
    }


    public static function getAdmin()
    {
        return self::where('is_admin', 1)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function getSingle($id)
    {
        return User::find($id);
    }

    public function getProfile()
    {
        if(!empty($this->profile_pic) && file_exists('public/assets/images/team/'.$this->profile_pic))
        {
            return url('public/assets/images/team/'.$this->profile_pic);
        }
        else
        {
            return "";
        }
    }

    public function getImage()
    {
        if(!empty($this->image_name) && file_exists(public_path('upload/user/' .$this->image_name)))
        {
            return url('upload/user/' .$this->image_name);
        }
        else
        {
            return url('upload/user/default_profile_pic.jpg');
        }
    }

    static public function getTotalUser()
    {
      return self::select('id')
                ->where('is_admin', '=', 0)
                ->where('is_delete', '=', 0)
                ->count();
    }

    static public function getTotalTodayUser()
    {
      return self::select('id')
                ->where('is_admin', '=', 0)
                ->where('is_delete', '=', 0)
                ->whereDate('created_at', '=', date('Y-m-d'))
                ->count();
    }

    static public function getTotalUserMonth($start_date, $end_date)
    {
      return self::select('id')
                ->where('is_admin', '=', 0)
                ->where('is_delete', '=', 0)
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->count();
    }

    public static function getUser() 
    {
        $return = User::select('users.*');
                  if(!empty(Request::get('id')))
                  {
                    $return = $return->where('id', '=', Request::get('id'));
                  }

                  if(!empty(Request::get('name')))
                  {
                    $return = $return->where('name', 'like', '%'.Request::get('name').'%');
                  }

                  if(!empty(Request::get('email')))
                  {
                    $return = $return->where('email', 'like', '%'.Request::get('email').'%');
                  }

                  if(!empty(Request::get('from_date')))
                  {
                    $return = $return->whereDate('created_at', '>=', Request::get('from_date'));
                  }

                  if(!empty(Request::get('to_date')))
                  {
                    $return = $return->whereDate('created_at', '<=', Request::get('to_date'));
                  }

        $return = $return-> where('is_admin', '=', 0)
                       -> where('is_delete', '=', 0)
                       -> orderby('id' , 'desc')
                       ->paginate(20);
                return $return;
    }
   
    
    public static function checkEmail($email)
    {
        return User::select('users.*')
                       -> where('email', '=', $email)
                       ->first();
    }


}
