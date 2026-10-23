<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityResourceController extends Controller
{
    /**
     * Validate that the URL is an HTTP(S) Google Maps URL.
     */
    private function isGoogleMapsUrl(string $url, callable $fail): void
    {
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        $allowedHosts = [
            'google.com',
            'www.google.com',
            'maps.google.com',
            'maps.app.goo.gl',
            'goo.gl',
        ];

        if (
            !in_array($scheme, ['http', 'https'], true) ||
            !in_array($host, $allowedHosts, true)
        ) {
            $fail('Please enter a valid Google Maps link or embed URL.');
        }
    }

    /**
     * Create or update the facility's single Google Maps resource.
     */
    public function store(Request $request, Facility $facility)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'url' => [
                'required',
                'url',
                'max:2048',
                function ($attribute, $value, $fail) {
                    $this->isGoogleMapsUrl($value, $fail);
                },
            ],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $resource = DB::transaction(function () use ($facility, $data) {
            $resources = $facility->resources()
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $resource = $resources->first();

            if ($resource) {
                $resource->update($data);

                // Remove duplicates left by previous implementations.
                $duplicateIds = $resources->skip(1)->pluck('id');

                if ($duplicateIds->isNotEmpty()) {
                    FacilityResource::whereIn('id', $duplicateIds)->delete();
                }
            } else {
                $resource = $facility->resources()->create($data);
            }

            return $resource->fresh();
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Facility Google Maps resource saved successfully.',
                'resource' => $resource,
            ]);
        }

        return back()->with(
            'success',
            'Facility Google Maps resource saved successfully.'
        );
    }

    /**
     * Update a resource only when it belongs to the supplied facility.
     */
    public function update(
        Request $request,
        Facility $facility,
        FacilityResource $resource
    ) {
        abort_unless(
            (int) $resource->facility_id === (int) $facility->id,
            404
        );

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'url' => [
                'required',
                'url',
                'max:2048',
                function ($attribute, $value, $fail) {
                    $this->isGoogleMapsUrl($value, $fail);
                },
            ],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $resource->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Facility Google Maps resource updated successfully.',
            'resource' => $resource->fresh(),
        ]);
    }

    /**
     * Delete the facility's resource.
     */
    public function destroy(
        Facility $facility,
        FacilityResource $resource
    ) {
        abort_unless(
            (int) $resource->facility_id === (int) $facility->id,
            404
        );

        $resource->delete();

        return response()->json([
            'success' => true,
            'message' => 'Facility Google Maps resource deleted successfully.',
        ]);
    }

    /**
     * Display the resource only to students enrolled at this facility.
     */
    public function studentIndex(Facility $facility)
    {
        $studentId = auth()->id();

        abort_unless($studentId, 403);

        $isEnrolled = DB::table('enrollment_tbls')
            ->join(
                'course_tbls',
                'course_tbls.id',
                '=',
                'enrollment_tbls.course_id'
            )
            ->where('enrollment_tbls.user_id', $studentId)
            ->where('course_tbls.facility_id', $facility->id)
            ->whereIn('enrollment_tbls.status', ['active', 'completed'])
            ->exists();

        abort_unless($isEnrolled, 403);

        // Adjust these field names if your facilities table uses
        // a different column for its address.
        $facilityAddress = $facility->address;

$resources = $facility->resources()
    ->orderByDesc('updated_at')
    ->limit(1)
    ->get()
    ->map(function (FacilityResource $resource) use ($facilityAddress) {
        $resource->map_embed_url = $this->getMapEmbedUrl(
            $resource->url,
            $facilityAddress
        );

        return $resource;
    });

        return view(
            'student.facility_resources',
            compact('facility', 'resources')
        );
    }

    /**
     * Return an embeddable URL for supported Google Maps formats.
     *
     * Direct embed URLs are preserved.
     * Regular Maps URLs are converted when a place or query is available.
     * Short share links fall back to the facility address.
     */
    private function getMapEmbedUrl(
        string $url,
        ?string $facilityAddress = null
    ): ?string {
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = (string) parse_url($url, PHP_URL_PATH);
        $query = (string) parse_url($url, PHP_URL_QUERY);

        $allowedHosts = [
            'google.com',
            'www.google.com',
            'maps.google.com',
            'maps.app.goo.gl',
            'goo.gl',
        ];

        if (
            !in_array($scheme, ['http', 'https'], true) ||
            !in_array($host, $allowedHosts, true)
        ) {
            return null;
        }

        $standardGoogleHosts = [
            'google.com',
            'www.google.com',
            'maps.google.com',
        ];

        // Preserve Google-generated embed URLs.
        if (
            in_array($host, $standardGoogleHosts, true) &&
            str_contains($path, '/maps/embed')
        ) {
            return $url;
        }

        // Convert regular Google Maps URLs when the place is identifiable.
        if (
            in_array($host, $standardGoogleHosts, true) &&
            str_contains($path, '/maps')
        ) {
            parse_str($query, $params);

            $place = $params['q'] ?? $params['query'] ?? null;

            if (
                !$place &&
                preg_match('~/(?:maps/)?place/([^/]+)~', $path, $matches)
            ) {
                $place = rawurldecode($matches[1]);
            }

            if ($place) {
                return 'https://www.google.com/maps?q='
                    . rawurlencode($place)
                    . '&output=embed';
            }
        }

        // A short link cannot be converted to its exact pin by parsing
        // its URL. Use the facility address as a fallback.
        if (
            in_array($host, ['maps.app.goo.gl', 'goo.gl'], true) &&
            !empty($facilityAddress)
        ) {
            return 'https://www.google.com/maps?q='
                . rawurlencode($facilityAddress)
                . '&output=embed';
        }

        return null;
    }
}
