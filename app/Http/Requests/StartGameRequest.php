<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartGameRequest extends FormRequest
{
    public function authorize()
    {
        $game = $this->route('game');
        return $game->activeScorers()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function rules()
    {
        $game = $this->route('game');
        $requiredStarters = $game->isVolleyball() ? 6 : 5;
        $minRosterSize = $game->isVolleyball() ? 6 : 5;

        return [
            'team1_roster' => 'required|json',
            'team2_roster' => 'required|json',
            'team1_starters' => "required|json|array:$requiredStarters",
            'team2_starters' => "required|json|array:$requiredStarters",
            'interface_mode' => 'required|in:all_in_one,separated',
        ];
    }

    public function messages()
    {
        return [
            'authorize' => 'You must be the scorer to start the game',
            'interface_mode.required' => 'Select All-in-One or Separated mode',
        ];
    }
}