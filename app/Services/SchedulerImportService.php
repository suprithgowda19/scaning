<?php

namespace App\Services;

use App\Models\Venue;
use App\Models\Screen;
use App\Models\Scheduler;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SchedulerImportService
{
    /* ======================================================
     | PUBLIC API
     ====================================================== */

    public function create(array $input): Scheduler
    {
        return DB::transaction(fn () => $this->persist(new Scheduler(), $input));
    }

    public function update(Scheduler $scheduler, array $input): Scheduler
    {
        return DB::transaction(fn () => $this->persist($scheduler, $input));
    }

    /**
     * Used for preview / dry-run validation
     */
    public function normalizeOnly(array $input): array
    {
        $venue  = $this->resolveVenue($input);
        $screen = $this->resolveScreen($venue->id, $input);

        $this->validateMovieOrEvent($input);
        [$language, $duration] = $this->resolveMovieMeta($input);
        $this->validateTiming($input);

        return [
            'venue_id'   => $venue->id,
            'screen_id'  => $screen->id,
            'language'   => $language,
            'duration'   => $duration,
            'show_date'  => $input['show_date'],
            'start_time' => $input['start_time'],
        ];
    }

    /* ======================================================
     | CORE PERSISTENCE
     ====================================================== */

    protected function persist(Scheduler $scheduler, array $input): Scheduler
    {
        $venue  = $this->resolveVenue($input);
        $screen = $this->resolveScreen($venue->id, $input);

        $this->validateMovieOrEvent($input);
        [$language, $duration] = $this->resolveMovieMeta($input);
        $this->validateTiming($input);

        $scheduler->fill([
            'venue_id'    => $venue->id,
            'screen_id'   => $screen->id,

            'movie_title' => $input['movie_title'] ?? null,
            'event_title' => $input['event_title'] ?? null,

            'language'    => $language,
            'duration'    => $duration,

            'show_date'   => $input['show_date'],
            'start_time'  => $input['start_time'],
            'is_active'   => $input['is_active'] ?? true,
        ]);

        $scheduler->save();

        return $scheduler;
    }

    /* ======================================================
     | MOVIE / EVENT LOGIC (FINAL & STRICT)
     ====================================================== */

    protected function validateMovieOrEvent(array $input): void
    {
        $movie = trim($input['movie_title'] ?? '');
        $event = trim($input['event_title'] ?? '');

        if ($movie === '' && $event === '') {
            throw ValidationException::withMessages([
                'content' => 'Either movie title or event title must be provided',
            ]);
        }

        if ($movie !== '' && $event !== '') {
            throw ValidationException::withMessages([
                'content' => 'Only one of movie title or event title is allowed',
            ]);
        }
    }

    /**
     * Returns [language, duration]
     */
    protected function resolveMovieMeta(array $input): array
    {
        $movieTitle = trim($input['movie_title'] ?? '');

        // ===== EVENT =====
        if ($movieTitle === '') {
            return [
                $input['language'] ?? null,
                $input['duration'] ?? null,
            ];
        }

        // ===== MOVIE (STRICT) =====
        $normalized = $this->normalizeTitle($movieTitle);

        $movie = Movie::whereRaw(
            'LOWER(TRIM(original_title)) = ?',
            [$normalized]
        )->first();

        if (! $movie) {
            throw ValidationException::withMessages([
                'movie_title' => "Movie not found in master list: {$movieTitle}",
            ]);
        }

        return [
            $movie->language,
            $movie->duration ?? ($input['duration'] ?? null),
        ];
    }

    protected function normalizeTitle(string $title): string
    {
        return strtolower(
            preg_replace('/\s+/', ' ', trim($title))
        );
    }

    /* ======================================================
     | VENUE / SCREEN RESOLUTION
     ====================================================== */

    protected function resolveVenue(array $input): Venue
    {
        if (! empty($input['venue_name'])) {
            $venue = Venue::where('name', trim($input['venue_name']))->first();

            if (! $venue) {
                throw ValidationException::withMessages([
                    'venue_name' => "Unknown venue: {$input['venue_name']}",
                ]);
            }

            return $venue;
        }

        $venue = Venue::first();

        if (! $venue) {
            throw ValidationException::withMessages([
                'venue' => 'No venue exists in the system',
            ]);
        }

        return $venue;
    }

    protected function resolveScreen(int $venueId, array $input): Screen
    {
        if (empty($input['screen_name'])) {
            throw ValidationException::withMessages([
                'screen_name' => 'Screen name is required',
            ]);
        }

        $screen = Screen::where('venue_id', $venueId)
            ->where('name', trim($input['screen_name']))
            ->first();

        if (! $screen) {
            throw ValidationException::withMessages([
                'screen_name' =>
                    "Unknown screen '{$input['screen_name']}' for this venue",
            ]);
        }

        return $screen;
    }

    /* ======================================================
     | TIME VALIDATION
     ====================================================== */

    protected function validateTiming(array $input): void
    {
        if (empty($input['show_date'])) {
            throw ValidationException::withMessages([
                'show_date' => 'Show date is required',
            ]);
        }

        if (empty($input['start_time'])) {
            throw ValidationException::withMessages([
                'start_time' => 'Start time is required',
            ]);
        }
    }
}
