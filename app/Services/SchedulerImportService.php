<?php

namespace App\Services;

use App\Models\Screen;
use App\Models\Slot;
use App\Models\Movie;
use App\Models\ScreenSlotAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchedulerImportService
{
    /**
     * Import scheduler rows into normalized tables for a given venue
     *
     * @param int $venueId
     * @return void
     */
    public function importForVenue(int $venueId): void
    {
        DB::table('scheduler')
            ->where('status', 1)
            ->orderBy('id')
            ->chunk(200, function ($rows) use ($venueId) {

                DB::transaction(function () use ($rows, $venueId) {

                    foreach ($rows as $row) {

                        /* -------------------------------------------------
                         |  HARD GUARDS — NEVER TRUST SCHEDULER INPUT
                         |--------------------------------------------------*/

                        // Screen name
                        $screenName = trim((string) $row->screen_no);
                        if ($screenName === '') {
                            Log::warning('Scheduler import skipped: empty screen_no', [
                                'scheduler_id' => $row->id ?? null,
                            ]);
                            continue;
                        }

                        // Show date
                        if (empty($row->date)) {
                            Log::warning('Scheduler import skipped: missing date', [
                                'scheduler_id' => $row->id ?? null,
                            ]);
                            continue;
                        }

                        // Show start time
                        if (empty($row->time)) {
                            Log::warning('Scheduler import skipped: missing time', [
                                'scheduler_id' => $row->id ?? null,
                            ]);
                            continue;
                        }

                        // Runtime
                        $runtime = (int) $row->run_time;
                        if ($runtime <= 0) {
                            Log::warning('Scheduler import skipped: invalid runtime', [
                                'scheduler_id' => $row->id ?? null,
                                'runtime'      => $row->run_time,
                            ]);
                            continue;
                        }

                        /* -------------------------------------------------
                         |  1. Resolve Screen (venue-scoped)
                         |--------------------------------------------------*/
                        $screen = Screen::firstOrCreate(
                            [
                                'venue_id' => $venueId,
                                'name'     => $screenName,
                            ],
                            [
                                'display_name' => $row->screen_name ?: null,
                                'capacity'     => 300, // default, editable later
                            ]
                        );

                        /* -------------------------------------------------
                         |  2. Resolve Movie (scheduler-idempotent)
                         |--------------------------------------------------*/
                        $movie = Movie::updateOrCreate(
                            [
                                'external_film_id' => $row->film_id,
                            ],
                            [
                                'title'          => $row->movie_title_eng ?: $row->movie_title,
                                'original_title' => $row->movie_title,
                                'duration'       => $runtime,
                                'language'       => $row->langauge,
                                'country'        => $row->country,
                                'year'           => $row->year,
                                'director'       => $row->director,
                                'category'       => $row->category,
                            ]
                        );

                        /* -------------------------------------------------
                         |  3. Resolve Slot (global time window)
                         |--------------------------------------------------*/
                        try {
                            $start = Carbon::parse($row->time);
                        } catch (\Throwable $e) {
                            Log::warning('Scheduler import skipped: invalid time format', [
                                'scheduler_id' => $row->id ?? null,
                                'time'         => $row->time,
                            ]);
                            continue;
                        }

                        $end = (clone $start)->addMinutes($runtime);

                        $slot = Slot::firstOrCreate([
                            'start_time' => $start->format('H:i:s'),
                            'end_time'   => $end->format('H:i:s'),
                        ]);

                        /* -------------------------------------------------
                         |  4. Create Screen Slot Assignment (SSA)
                         |--------------------------------------------------*/
                        ScreenSlotAssignment::firstOrCreate(
                            [
                                'screen_id' => $screen->id,
                                'slot_id'   => $slot->id,
                                'show_date' => $row->date,
                            ],
                            [
                                'movie_id'  => $movie->id,
                            ]
                        );
                    }
                });
            });
    }
}
