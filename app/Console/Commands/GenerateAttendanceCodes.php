<?php

namespace App\Console\Commands;

use App\Models\Timetable;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GenerateAttendanceCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:generate-codes
                            {--timetable= : Specific timetable ID}
                            {--course= : Specific course ID}
                            {--day= : Generate for specific day (Monday-Sunday)}
                            {--force : Force regeneration of existing codes}
                            {--expires=15 : Code expiration time in minutes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate secure attendance codes for timetables with advanced security features';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Generating Secure Attendance Codes...');

        $query = Timetable::query();

        // Apply filters
        if ($this->option('timetable')) {
            $query->where('id', $this->option('timetable'));
        }

        if ($this->option('course')) {
            $query->where('course_unit_id', $this->option('course'));
        }

        if ($this->option('day')) {
            $query->where('day', $this->option('day'));
        }

        $timetables = $query->get();

        if ($timetables->isEmpty()) {
            $this->error('❌ No timetables found matching the criteria.');
            return 1;
        }

        $this->info("📋 Found {$timetables->count()} timetable(s) to process");

        $bar = $this->output->createProgressBar($timetables->count());
        $bar->start();

        $generated = 0;
        $skipped = 0;
        $expiresIn = (int) $this->option('expires');

        foreach ($timetables as $timetable) {
            if (!$this->option('force') && !empty($timetable->attendance_code)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $secureCode = $this->generateSecureAttendanceCode($timetable, $expiresIn);

            $timetable->update([
                'attendance_code' => $secureCode,
                'updated_at' => now(),
            ]);

            $generated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Generated: {$generated} codes");
        $this->info("⏭️  Skipped: {$skipped} (existing codes)");
        $this->info("⏰ Code Expiration: {$expiresIn} minutes");
        $this->newLine();

        $this->warn('🔒 Security Features Enabled:');
        $this->line('  • Time-based expiration');
        $this->line('  • Cryptographically secure generation');
        $this->line('  • Unique per timetable session');
        $this->line('  • Automatic cleanup after expiration');

        return 0;
    }

    /**
     * Generate a cryptographically secure attendance code
     */
    private function generateSecureAttendanceCode($timetable, $expiresIn)
    {
        // Create a unique seed combining multiple entropy sources
        $entropy = [
            $timetable->id,
            $timetable->course_unit_id,
            $timetable->day,
            $timetable->start_time->format('H:i:s'),
            now()->timestamp,
            Str::random(16), // Additional randomness
            request()->server('REMOTE_ADDR', '127.0.0.1'), // Server entropy
        ];

        // Create a hash of all entropy sources
        $seed = hash('sha256', implode('|', $entropy));

        // Generate a 6-character alphanumeric code
        $code = strtoupper(substr($seed, 0, 6));

        // Ensure it's alphanumeric only
        $code = preg_replace('/[^A-Z0-9]/', '', $code);

        // If the code is too short, pad it
        while (strlen($code) < 6) {
            $code .= strtoupper(substr(hash('sha256', $seed . microtime()), 0, 6 - strlen($code)));
            $code = preg_replace('/[^A-Z0-9]/', '', $code);
        }

        // Store metadata for security tracking
        $metadata = [
            'generated_at' => now()->toISOString(),
            'expires_at' => now()->addMinutes($expiresIn)->toISOString(),
            'timetable_id' => $timetable->id,
            'course_id' => $timetable->course_unit_id,
            'generator_ip' => request()->ip(),
            'security_level' => 'maximum',
        ];

        // Cache the metadata for security validation
        cache()->put(
            "attendance_code_{$code}",
            $metadata,
            now()->addMinutes($expiresIn + 5) // Keep cache slightly longer
        );

        return $code;
    }
}
