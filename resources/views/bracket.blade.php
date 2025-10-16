<!DOCTYPE html>
<html>
<head>
    <title>{{ $tournament->name }} Bracket</title>
    <style>
        body { font-family: sans-serif; }
        .bracket-container { width: 100%; }
        .round { display: inline-block; vertical-align: top; margin-right: 20px; }
        .game { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>{{ $tournament->name }} - Bracket</h2>
    <div class="bracket-container">
        @foreach ($tournament->rounds as $round)
            <div class="round">
                <h4>Round {{ $loop->iteration }}</h4>
                @foreach ($round->games as $game)
                    <div class="game">
                        @foreach ($game->teams as $team)
                            <div>{{ $team->name }}</div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</body>
</html>
