<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Job;
use App\Models\OfferLetter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RecruitmentCalendarController extends Controller
{
    /**
     * Resolve company name filter for current user.
     */
    protected function getCompanyFilter()
    {
        $user = auth()->user();
        if (!$user || $user->hasRole('Super Admin')) {
            return null;
        }

        $profile = $user->currentCompanyProfile();
        return $profile ? $profile->company_name : null;
    }

    /**
     * Display Recruitment Calendar page.
     */
    public function index()
    {
        $companyName = $this->getCompanyFilter();

        // 1. Upcoming Interviews Query
        $interviewQuery = Interview::with(['application.user.candidateProfile', 'application.job'])
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>=', now()->startOfDay())
            ->orderBy('scheduled_at', 'asc');

        if ($companyName) {
            $interviewQuery->whereHas('application.job', function($j) use ($companyName) {
                $j->where('company_name', 'LIKE', '%' . $companyName . '%');
            });
        }
        $upcomingInterviews = $interviewQuery->take(6)->get();

        // 2. Upcoming Job Deadlines Query
        $jobQuery = Job::whereNotNull('deadline')
            ->where('deadline', '>=', now()->startOfDay())
            ->orderBy('deadline', 'asc');

        if ($companyName) {
            $jobQuery->where('company_name', 'LIKE', '%' . $companyName . '%');
        }
        $upcomingDeadlines = $jobQuery->take(4)->get();

        // 3. Quick Stats (This Month)
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $interviewsThisMonthCount = Interview::whereBetween('scheduled_at', [$startOfMonth, $endOfMonth])
            ->when($companyName, function($q) use ($companyName) {
                $q->whereHas('application.job', fn($j) => $j->where('company_name', 'LIKE', "%{$companyName}%"));
            })->count();

        $deadlinesThisMonthCount = Job::whereBetween('deadline', [$startOfMonth, $endOfMonth])
            ->when($companyName, function($q) use ($companyName) {
                $q->where('company_name', 'LIKE', "%{$companyName}%");
            })->count();

        $offersThisMonthCount = OfferLetter::whereBetween('expiration_date', [$startOfMonth, $endOfMonth])
            ->when($companyName, function($q) use ($companyName) {
                $q->whereHas('job', fn($j) => $j->where('company_name', 'LIKE', "%{$companyName}%"));
            })->count();

        return view('admin.calendar.index', compact(
            'upcomingInterviews',
            'upcomingDeadlines',
            'interviewsThisMonthCount',
            'deadlinesThisMonthCount',
            'offersThisMonthCount'
        ));
    }

    /**
     * JSON API Endpoint returning FullCalendar events.
     */
    public function events(Request $request)
    {
        $companyName = $this->getCompanyFilter();
        $events = [];

        // 1. Interview Events
        $interviewQuery = Interview::with(['application.user.candidateProfile', 'application.job'])
            ->whereNotNull('scheduled_at');

        if ($companyName) {
            $interviewQuery->whereHas('application.job', function($j) use ($companyName) {
                $j->where('company_name', 'LIKE', '%' . $companyName . '%');
            });
        }

        foreach ($interviewQuery->get() as $interview) {
            if ($interview->scheduled_at) {
                $candidateName = $interview->application->user->name ?? 'Kandidat';
                $candidateEmail = $interview->application->user->email ?? '';
                $jobTitle = $interview->application->job->title ?? 'Pekerjaan';
                $startTime = $interview->scheduled_at;
                $endTime = $startTime->copy()->addHour();
                $locationOrLink = $interview->location_or_link ?: 'Online Meeting';
                $notes = $interview->notes ?: 'Wawancara dengan kandidat ' . $candidateName;

                // 1-Click Google Calendar URL generator
                $gCalStart = $startTime->utc()->format('Ymd\THis\Z');
                $gCalEnd = $endTime->utc()->format('Ymd\THis\Z');
                $gCalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE"
                    . "&text=" . urlencode("Wawancara: {$candidateName} - {$jobTitle}")
                    . "&dates={$gCalStart}/{$gCalEnd}"
                    . "&details=" . urlencode("Sesi Wawancara Kandidat:\n- Nama: {$candidateName} ({$candidateEmail})\n- Posisi: {$jobTitle}\n- Link/Lokasi: {$locationOrLink}\n- Catatan: {$notes}")
                    . "&location=" . urlencode($locationOrLink);

                $events[] = [
                    'id' => 'interview_' . $interview->id,
                    'title' => "Wawancara: {$candidateName}",
                    'start' => $startTime->toIso8601String(),
                    'end' => $endTime->toIso8601String(),
                    'allDay' => false,
                    'display' => 'block',
                    'className' => 'event-interview',
                    'extendedProps' => [
                        'category' => 'interview',
                        'category_label' => 'Wawancara Rekrutmen',
                        'candidate_name' => $candidateName,
                        'candidate_email' => $candidateEmail,
                        'candidate_initial' => strtoupper(substr($candidateName, 0, 1)),
                        'job_title' => $jobTitle,
                        'formatted_time' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i') . ' WIB',
                        'formatted_date' => $startTime->translatedFormat('l, d F Y'),
                        'type' => $interview->type ?: 'Online Video',
                        'location_or_link' => $locationOrLink,
                        'notes' => $notes,
                        'google_calendar_url' => $gCalUrl,
                        'action_url' => route('admin.applications.show', $interview->application_id),
                    ],
                ];
            }
        }

        // 2. Job Closing Deadline Events
        $jobQuery = Job::whereNotNull('deadline');
        if ($companyName) {
            $jobQuery->where('company_name', 'LIKE', '%' . $companyName . '%');
        }

        foreach ($jobQuery->get() as $job) {
            if ($job->deadline) {
                $deadlineDate = Carbon::parse($job->deadline);
                $gCalDate = $deadlineDate->format('Ymd');
                $gCalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE"
                    . "&text=" . urlencode("Deadline Lowongan: {$job->title}")
                    . "&dates={$gCalDate}/{$gCalDate}"
                    . "&details=" . urlencode("Batas akhir pendaftaran lowongan posisi {$job->title} ({$job->company_name}).");

                $events[] = [
                    'id' => 'job_' . $job->id,
                    'title' => "Batas: {$job->title}",
                    'start' => $deadlineDate->format('Y-m-d'),
                    'allDay' => true,
                    'display' => 'block',
                    'className' => 'event-deadline',
                    'extendedProps' => [
                        'category' => 'deadline',
                        'category_label' => 'Deadline Lowongan',
                        'candidate_name' => null,
                        'job_title' => $job->title,
                        'company_name' => $job->company_name,
                        'formatted_time' => 'Batas Akhir (23:59 WIB)',
                        'formatted_date' => $deadlineDate->translatedFormat('l, d F Y'),
                        'location_or_link' => 'Portal Karir',
                        'notes' => 'Penutupan penerimaan berkas pelamar baru.',
                        'google_calendar_url' => $gCalUrl,
                        'action_url' => route('admin.jobs.show', $job->id),
                    ],
                ];
            }
        }

        // 3. Offer Letter Expiration Events
        $offerQuery = OfferLetter::with('user', 'job')->whereNotNull('expiration_date');
        if ($companyName) {
            $offerQuery->whereHas('job', function($j) use ($companyName) {
                $j->where('company_name', 'LIKE', '%' . $companyName . '%');
            });
        }

        foreach ($offerQuery->get() as $offer) {
            if ($offer->expiration_date) {
                $expDate = Carbon::parse($offer->expiration_date);
                $candidateName = $offer->user->name ?? 'Kandidat';
                $gCalDate = $expDate->format('Ymd');
                $gCalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE"
                    . "&text=" . urlencode("Expired Offer: {$candidateName}")
                    . "&dates={$gCalDate}/{$gCalDate}"
                    . "&details=" . urlencode("Batas masa berlaku Offer Letter untuk {$candidateName} (Posisi {$offer->position_title}).");

                $events[] = [
                    'id' => 'offer_' . $offer->id,
                    'title' => "Offer Expired: {$candidateName}",
                    'start' => $expDate->format('Y-m-d'),
                    'allDay' => true,
                    'display' => 'block',
                    'className' => 'event-offer',
                    'extendedProps' => [
                        'category' => 'offer',
                        'category_label' => 'Masa Berlaku Offer Letter',
                        'candidate_name' => $candidateName,
                        'job_title' => $offer->position_title ?? 'Pekerjaan',
                        'formatted_time' => 'Batas Tanggapan Offer',
                        'formatted_date' => $expDate->translatedFormat('l, d F Y'),
                        'location_or_link' => 'Portal Offer Letter',
                        'notes' => 'Batas konfirmasi penerimaan penawaran kerja oleh kandidat.',
                        'google_calendar_url' => $gCalUrl,
                        'action_url' => route('admin.applications.show', $offer->application_id),
                    ],
                ];
            }
        }

        return response()->json($events);
    }

    /**
     * Export all recruitment events as an iCalendar (.ics) feed.
     */
    public function exportIcs()
    {
        $companyName = $this->getCompanyFilter();
        $appName = config('app.name', 'TalentFlow ATS');
        
        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//TalentFlow//Recruitment Calendar//ID\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "METHOD:PUBLISH\r\n";
        $ics .= "X-WR-CALNAME:Jadwal Rekrutmen - " . ($companyName ?: $appName) . "\r\n";
        $ics .= "X-WR-TIMEZONE:Asia/Jakarta\r\n";

        // Interviews
        $interviewQuery = Interview::with(['application.user', 'application.job'])->whereNotNull('scheduled_at');
        if ($companyName) {
            $interviewQuery->whereHas('application.job', fn($j) => $j->where('company_name', 'LIKE', "%{$companyName}%"));
        }

        foreach ($interviewQuery->get() as $interview) {
            $startTime = $interview->scheduled_at->utc()->format('Ymd\THis\Z');
            $endTime = $interview->scheduled_at->copy()->addHour()->utc()->format('Ymd\THis\Z');
            $candidateName = $interview->application->user->name ?? 'Kandidat';
            $jobTitle = $interview->application->job->title ?? 'Pekerjaan';
            $summary = "Wawancara: {$candidateName} ({$jobTitle})";
            $desc = "Sesi Wawancara Rekrutmen:\nKandidat: {$candidateName}\nPosisi: {$jobTitle}\nLokasi/Link: " . ($interview->location_or_link ?: 'Online');
            $loc = $interview->location_or_link ?: 'Online Meeting';
            $uid = "interview-{$interview->id}@" . parse_url(config('app.url'), PHP_URL_HOST);

            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "UID:{$uid}\r\n";
            $ics .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
            $ics .= "DTSTART:{$startTime}\r\n";
            $ics .= "DTEND:{$endTime}\r\n";
            $ics .= "SUMMARY:{$summary}\r\n";
            $ics .= "DESCRIPTION:" . str_replace("\n", "\\n", $desc) . "\r\n";
            $ics .= "LOCATION:{$loc}\r\n";
            $ics .= "STATUS:CONFIRMED\r\n";
            $ics .= "END:VEVENT\r\n";
        }

        // Job Deadlines
        $jobQuery = Job::whereNotNull('deadline');
        if ($companyName) {
            $jobQuery->where('company_name', 'LIKE', "%{$companyName}%");
        }

        foreach ($jobQuery->get() as $job) {
            $deadlineDate = Carbon::parse($job->deadline)->format('Ymd');
            $summary = "Deadline Lowongan: {$job->title}";
            $uid = "job-deadline-{$job->id}@" . parse_url(config('app.url'), PHP_URL_HOST);

            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "UID:{$uid}\r\n";
            $ics .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
            $ics .= "DTSTART;VALUE=DATE:{$deadlineDate}\r\n";
            $ics .= "DTEND;VALUE=DATE:{$deadlineDate}\r\n";
            $ics .= "SUMMARY:{$summary}\r\n";
            $ics .= "DESCRIPTION:Batas penutupan lowongan pekerjaan {$job->title}.\r\n";
            $ics .= "STATUS:CONFIRMED\r\n";
            $ics .= "END:VEVENT\r\n";
        }

        $ics .= "END:VCALENDAR\r\n";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="kalender_rekrutmen.ics"',
        ]);
    }
}

