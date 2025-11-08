<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTrackToPlaylistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'playlist_id' => ['required', 'uuid', 'exists:playlists,id'],
            'track_id' => ['nullable', 'string', 'max:191'],
            'track_name' => ['required', 'string', 'max:255'],
            'artist_name' => ['required', 'string', 'max:255'],
            'collection_name' => ['nullable', 'string', 'max:255'],
            'collection_artist_name' => ['nullable', 'string', 'max:255'],
            'primary_genre_name' => ['nullable', 'string', 'max:255'],
            'track_time_millis' => ['nullable', 'integer', 'min:0'],
            'release_date' => ['nullable', 'date'],
            'preview_url' => ['nullable', 'url'],
            'artwork_url' => ['nullable', 'url'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'track_time_millis' => $this->track_time_millis !== null && $this->track_time_millis !== ''
                ? (int) $this->track_time_millis
                : null,
            'release_date' => $this->release_date ?: null,
        ]);
    }
}
