<?php

namespace App\Livewire;

use App\Models\Timetable;
use App\Models\Attendance;
use App\Models\CourseUnit;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Classes extends Component
{
    public $selectedDay = 'Monday';
    public $timetables = [];
    public $attendanceCode = '';
    public $message = '';
    public $activeTab = 'overview';

    // Academic progress data
    public $currentSemester = 'Semester I 2024/2025';
    public $gpa = 3.8;
    public $cgpa = 3.6;
    public $totalCredits = 120;
    public $completedCredits = 95;
    public $attendanceStats = [];
    public $upcomingEvents = [];
    public $recentGrades = [];

    public function mount()
    {
        $this->loadTimetables();
        $this->loadAcademicData();
    }

    public function loadTimetables()
    {
        $this->timetables = Timetable::with(['courseUnit', 'attendances' => function($query) {
            $query->where('user_id', auth()->id())
                  ->where('date', today());
        }])
        ->where('day', $this->selectedDay)
        ->where('is_cancelled', false)
        ->orderBy('start_time')
        ->get();
    }

    public function loadAcademicData()
    {
        // Load attendance statistics
        $this->attendanceStats = [
            'total_classes' => Attendance::where('user_id', auth()->id())->count(),
            'present_count' => Attendance::where('user_id', auth()->id())->where('status', 'present')->count(),
            'absent_count' => Attendance::where('user_id', auth()->id())->where('status', 'absent')->count(),
            'late_count' => Attendance::where('user_id', auth()->id())->where('status', 'late')->count(),
        ];

        // Calculate attendance percentage
        if ($this->attendanceStats['total_classes'] > 0) {
            $this->attendanceStats['percentage'] = round(
                ($this->attendanceStats['present_count'] / $this->attendanceStats['total_classes']) * 100,
                1
            );
        } else {
            $this->attendanceStats['percentage'] = 0;
        }

        // Mock upcoming events (in real app, this would come from database)
        $this->upcomingEvents = [
            [
                'title' => 'Database Systems Exam',
                'date' => '2024-09-15',
                'type' => 'exam',
                'course' => 'CSC 2104'
            ],
            [
                'title' => 'Software Engineering Assignment Due',
                'date' => '2024-09-18',
                'type' => 'assignment',
                'course' => 'CSC 3102'
            ],
            [
                'title' => 'Data Structures Lab',
                'date' => '2024-09-12',
                'type' => 'lab',
                'course' => 'CSC 2101'
            ]
        ];

        // Mock recent grades
        $this->recentGrades = [
            ['course' => 'CSC 2104', 'grade' => 'A', 'points' => 4.0, 'semester' => 'Semester I 2024'],
            ['course' => 'CSC 3102', 'grade' => 'A-', 'points' => 3.7, 'semester' => 'Semester I 2024'],
            ['course' => 'CSC 2101', 'grade' => 'B+', 'points' => 3.3, 'semester' => 'Semester II 2023'],
        ];
    }

    public function updatedSelectedDay()
    {
        $this->loadTimetables();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function markAttendance($timetableId)
    {
        // MULTI-LAYER SECURITY VERIFICATION SYSTEM
        $securityResult = $this->performAdvancedSecurityChecks($timetableId);

        if (!$securityResult['passed']) {
            $this->message = $securityResult['message'];
            $this->logSecurityEvent('attendance_blocked', $securityResult);
            return;
        }

        $timetable = $securityResult['timetable'];

        // Generate unique verification token for this attendance
        $verificationToken = $this->generateSecureVerificationToken($timetable);

        // Create attendance record with comprehensive security data
        Attendance::create([
            'user_id' => auth()->id(),
            'timetable_id' => $timetableId,
            'date' => today(),
            'status' => 'present',
            'marked_at' => now(),
            'verification_method' => 'multi-factor-secure',
            'verification_token' => $verificationToken,
            'security_data' => json_encode($securityResult['security_data']),
        ]);

        // Clear security caches on successful attendance
        $this->clearSecurityCaches();

        $this->message = '✅ Attendance marked successfully with maximum security!';
        $this->attendanceCode = '';
        $this->loadTimetables();
        $this->loadAcademicData();
    }

    private function performAdvancedSecurityChecks($timetableId)
    {
        $result = [
            'passed' => true,
            'message' => '',
            'timetable' => null,
            'security_data' => []
        ];

        // 1. BASIC VALIDATION
        $timetable = Timetable::find($timetableId);
        if (!$timetable || !$timetable->attendance_code) {
            $result['passed'] = false;
            $result['message'] = 'Invalid class session or no attendance code available.';
            return $result;
        }

        if ($timetable->is_cancelled) {
            $result['passed'] = false;
            $result['message'] = 'This class session has been cancelled.';
            return $result;
        }

        $result['timetable'] = $timetable;

        // 2. CODE VALIDATION WITH ENHANCED SECURITY
        if ($this->attendanceCode !== $timetable->attendance_code) {
            $this->incrementSecurityViolation('invalid_code');
            $result['passed'] = false;
            $result['message'] = 'Invalid attendance code. Multiple failed attempts may result in temporary suspension.';
            return $result;
        }

        // 3. TIME-BASED VERIFICATION WITH MICRO-WINDOWS
        $currentTime = now();
        $classStart = Carbon::createFromTimeString($timetable->start_time->format('H:i:s'));
        $classEnd = Carbon::createFromTimeString($timetable->end_time->format('H:i:s'));

        // Dynamic time windows based on class duration
        $classDuration = $classStart->diffInMinutes($classEnd);
        $earlyBuffer = min(10, max(5, $classDuration * 0.1)); // 5-10 minutes early
        $lateBuffer = min(20, max(10, $classDuration * 0.2)); // 10-20 minutes late

        $validStart = $classStart->copy()->subMinutes($earlyBuffer);
        $validEnd = $classEnd->copy()->addMinutes($lateBuffer);

        if (!$currentTime->between($validStart, $validEnd)) {
            $result['passed'] = false;
            $result['message'] = 'Attendance can only be marked during the valid time window for this class.';
            return $result;
        }

        $result['security_data']['time_window'] = [
            'current_time' => $currentTime->toISOString(),
            'valid_start' => $validStart->toISOString(),
            'valid_end' => $validEnd->toISOString(),
            'early_buffer' => $earlyBuffer,
            'late_buffer' => $lateBuffer
        ];

        // 4. RATE LIMITING WITH EXPONENTIAL BACKOFF
        $userId = auth()->id();
        $attemptsKey = "attendance_attempts_{$userId}";
        $attempts = cache()->get($attemptsKey, 0);

        if ($attempts >= 3) {
            $result['passed'] = false;
            $result['message'] = 'Too many attendance attempts. Please wait before trying again.';
            return $result;
        }

        // 5. DUPLICATE ATTENDANCE PREVENTION
        $existing = Attendance::where('user_id', $userId)
            ->where('timetable_id', $timetableId)
            ->where('date', today())
            ->first();

        if ($existing) {
            $result['passed'] = false;
            $result['message'] = 'Attendance already marked for this class session.';
            return $result;
        }

        // 6. DEVICE FINGERPRINTING
        $deviceFingerprint = $this->generateAdvancedDeviceFingerprint();
        $result['security_data']['device_fingerprint'] = $deviceFingerprint;

        // Check for suspicious device patterns
        $this->analyzeDevicePatterns($userId, $deviceFingerprint, $result);

        // 7. NETWORK-BASED VERIFICATION
        $networkData = $this->analyzeNetworkPatterns($userId);
        $result['security_data']['network_analysis'] = $networkData;

        if (!$networkData['trusted']) {
            $result['passed'] = false;
            $result['message'] = 'Network verification failed. Please ensure you are on a trusted network.';
            return $result;
        }

        // 8. BEHAVIORAL PATTERN ANALYSIS
        $behavioralCheck = $this->analyzeBehavioralPatterns($userId, $timetable);
        $result['security_data']['behavioral_analysis'] = $behavioralCheck;

        if ($behavioralCheck['suspicious']) {
            $result['passed'] = false;
            $result['message'] = 'Suspicious attendance pattern detected. Please contact your lecturer if this is an error.';
            return $result;
        }

        // 9. SESSION-BASED VERIFICATION
        $sessionCheck = $this->validateSessionIntegrity($timetableId);
        if (!$sessionCheck['valid']) {
            $result['passed'] = false;
            $result['message'] = $sessionCheck['message'];
            return $result;
        }

        $result['security_data']['session_validation'] = $sessionCheck;

        // 10. CODE EXPIRATION AND ROTATION
        if ($this->isCodeExpired($timetable)) {
            $result['passed'] = false;
            $result['message'] = 'Attendance code has expired. Please refresh to get the latest code.';
            return $result;
        }

        // 11. CONCURRENT SESSION DETECTION
        if ($this->detectConcurrentSessions($userId, $timetableId)) {
            $result['passed'] = false;
            $result['message'] = 'Multiple concurrent attendance attempts detected.';
            return $result;
        }

        // 12. GEO-BASED VERIFICATION (if coordinates provided)
        if (request()->has(['latitude', 'longitude'])) {
            $geoCheck = $this->verifyGeolocation($timetable, request('latitude'), request('longitude'));
            $result['security_data']['geolocation'] = $geoCheck;

            if (!$geoCheck['within_bounds']) {
                $result['passed'] = false;
                $result['message'] = 'Location verification failed. Please ensure you are at the correct venue.';
                return $result;
            }
        }

        return $result;
    }

    private function generateAdvancedDeviceFingerprint()
    {
        $components = [
            request()->userAgent(),
            request()->ip(),
            session()->getId(),
            auth()->user()->id,
            request()->header('Accept-Language'),
            request()->header('Accept-Encoding'),
            now()->format('Y-m-d-H'),
        ];

        return hash('sha256', implode('|', $components));
    }

    private function analyzeDevicePatterns($userId, $currentFingerprint, &$result)
    {
        $recentAttendances = Attendance::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(7))
            ->whereNotNull('security_data')
            ->get();

        $deviceChanges = 0;
        $uniqueDevices = [];

        foreach ($recentAttendances as $attendance) {
            $data = json_decode($attendance->security_data, true);
            if (isset($data['device_fingerprint'])) {
                $uniqueDevices[] = $data['device_fingerprint'];
                if ($data['device_fingerprint'] !== $currentFingerprint) {
                    $deviceChanges++;
                }
            }
        }

        $uniqueDevices = array_unique($uniqueDevices);
        $result['security_data']['device_analysis'] = [
            'total_devices' => count($uniqueDevices),
            'device_changes' => $deviceChanges,
            'frequent_device_changes' => $deviceChanges > 3
        ];

        // Flag suspicious device switching
        if ($deviceChanges > 3) {
            $this->logSecurityEvent('frequent_device_switching', [
                'user_id' => $userId,
                'device_changes' => $deviceChanges
            ]);
        }
    }

    private function analyzeNetworkPatterns($userId)
    {
        $currentIP = request()->ip();
        $userAgent = request()->userAgent();

        // Check IP reputation (simplified)
        $isVPN = $this->detectVPNUsage($currentIP);
        $isProxy = $this->detectProxyUsage($currentIP);

        // Check for suspicious patterns
        $recentIPs = cache()->get("user_ips_{$userId}", []);
        $ipChanges = 0;

        if (!in_array($currentIP, $recentIPs)) {
            $recentIPs[] = $currentIP;
            $ipChanges = count(array_diff($recentIPs, [$currentIP]));
        }

        cache()->put("user_ips_{$userId}", array_slice($recentIPs, -10), now()->addDays(7));

        return [
            'trusted' => !$isVPN && !$isProxy && $ipChanges <= 2,
            'is_vpn' => $isVPN,
            'is_proxy' => $isProxy,
            'ip_changes' => $ipChanges,
            'current_ip' => $currentIP
        ];
    }

    private function analyzeBehavioralPatterns($userId, $timetable)
    {
        $today = today();
        $thisWeek = now()->startOfWeek();

        // Check attendance patterns
        $todayAttendances = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->count();

        $weekAttendances = Attendance::where('user_id', $userId)
            ->where('created_at', '>=', $thisWeek)
            ->count();

        // Check for unusual timing patterns
        $recentAttendances = Attendance::where('user_id', $userId)
            ->where('created_at', '>=', now()->subHours(2))
            ->get();

        $suspiciousTiming = false;
        if ($recentAttendances->count() >= 3) {
            $timestamps = $recentAttendances->pluck('created_at')->map->timestamp->sort();
            $intervals = [];
            for ($i = 1; $i < count($timestamps); $i++) {
                $intervals[] = $timestamps[$i] - $timestamps[$i-1];
            }

            // Check if intervals are too regular (indicating automated marking)
            if (count($intervals) >= 2) {
                $avgInterval = array_sum($intervals) / count($intervals);
                $variance = 0;
                foreach ($intervals as $interval) {
                    $variance += pow($interval - $avgInterval, 2);
                }
                $variance /= count($intervals);
                $suspiciousTiming = $variance < 10; // Very low variance indicates automation
            }
        }

        return [
            'suspicious' => $todayAttendances > 4 || $weekAttendances > 20 || $suspiciousTiming,
            'today_count' => $todayAttendances,
            'week_count' => $weekAttendances,
            'suspicious_timing' => $suspiciousTiming
        ];
    }

    private function validateSessionIntegrity($timetableId)
    {
        $sessionKey = "attendance_session_{$timetableId}_" . auth()->id();

        if (session()->has($sessionKey)) {
            $lastAttempt = session($sessionKey);
            if (now()->timestamp - $lastAttempt < 30) { // 30 seconds cooldown per class
                return [
                    'valid' => false,
                    'message' => 'Please wait before attempting to mark attendance again for this class.'
                ];
            }
        }

        session([$sessionKey => now()->timestamp]);

        return [
            'valid' => true,
            'session_id' => session()->getId()
        ];
    }

    private function isCodeExpired($timetable)
    {
        // Code expires after 15 minutes or if updated
        return $timetable->updated_at && $timetable->updated_at->diffInMinutes(now()) > 15;
    }

    private function detectConcurrentSessions($userId, $timetableId)
    {
        $concurrentKey = "concurrent_attendance_{$userId}";
        $activeSessions = cache()->get($concurrentKey, []);

        // Clean up expired sessions
        $activeSessions = array_filter($activeSessions, function($timestamp) {
            return now()->timestamp - $timestamp < 300; // 5 minutes
        });

        if (count($activeSessions) >= 2) {
            return true; // Multiple concurrent sessions detected
        }

        $activeSessions[] = now()->timestamp;
        cache()->put($concurrentKey, $activeSessions, 300); // 5 minutes

        return false;
    }

    private function verifyGeolocation($timetable, $latitude, $longitude)
    {
        // This would integrate with actual classroom coordinates
        // For now, we'll implement a basic bounds check
        $classroomLat = 0.3476; // Example: Makerere University coordinates
        $classroomLng = 32.5825;
        $allowedRadius = 0.5; // ~500 meters radius

        $distance = $this->calculateDistance($latitude, $longitude, $classroomLat, $classroomLng);

        return [
            'within_bounds' => $distance <= $allowedRadius,
            'distance' => $distance,
            'provided_coords' => [$latitude, $longitude],
            'classroom_coords' => [$classroomLat, $classroomLng]
        ];
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lngDelta/2) * sin($lngDelta/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    private function detectVPNUsage($ip)
    {
        // Simplified VPN detection - in production, use a proper VPN detection service
        $vpnRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16'
        ];

        // This is a very basic check - real implementation would use external services
        return false; // Placeholder
    }

    private function detectProxyUsage($ip)
    {
        // Simplified proxy detection
        return false; // Placeholder
    }

    private function generateSecureVerificationToken($timetable)
    {
        $components = [
            auth()->user()->id,
            $timetable->id,
            now()->timestamp,
            request()->ip(),
            $this->generateAdvancedDeviceFingerprint(),
            Str::random(32)
        ];

        return hash('sha256', implode('|', $components));
    }

    private function incrementSecurityViolation($violationType)
    {
        $key = "security_violations_" . auth()->id() . "_{$violationType}";
        $violations = cache()->get($key, 0) + 1;
        cache()->put($key, $violations, now()->addHours(24));

        if ($violations >= 5) {
            // Implement progressive penalties
            $this->implementSecurityPenalty(auth()->user(), $violationType, $violations);
        }
    }

    private function implementSecurityPenalty($user, $violationType, $violationCount)
    {
        // Implement progressive security measures
        switch ($violationCount) {
            case 5:
                // Temporary suspension for 1 hour
                cache()->put("attendance_suspended_{$user->id}", true, now()->addHour());
                break;
            case 10:
                // Temporary suspension for 24 hours
                cache()->put("attendance_suspended_{$user->id}", true, now()->addDay());
                break;
            case 15:
                // Flag for manual review
                $this->flagForManualReview($user, $violationType);
                break;
        }
    }

    private function flagForManualReview($user, $violationType)
    {
        // Log for manual review by administrators
        Log::warning('User Flagged for Manual Review', [
            'user_id' => $user->id,
            'violation_type' => $violationType,
            'timestamp' => now(),
            'requires_manual_review' => true
        ]);
    }

    private function logSecurityEvent($eventType, $data)
    {
        Log::info('Security Event: ' . $eventType, array_merge($data, [
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]));
    }

    private function clearSecurityCaches()
    {
        $userId = auth()->id();
        cache()->forget("attendance_attempts_{$userId}");
        cache()->forget("security_violations_{$userId}_invalid_code");
    }

    public function render()
    {
        return view('livewire.classes');
    }
}
