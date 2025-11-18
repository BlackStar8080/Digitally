<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} - Tournament Information</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            padding: 20px;
        }

        .page-header {
            text-align: center;
            padding: 15px 0;
            border-bottom: 2px solid #000;
            margin-bottom: 25px;
        }

        .logo-container {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .logo-left, .logo-right {
            display: table-cell;
            width: 20%;
            vertical-align: middle;
        }

        .logo-center {
            display: table-cell;
            width: 60%;
            text-align: center;
            vertical-align: middle;
        }

        .logo-left img, .logo-right img {
            width: 60px;
            height: auto;
        }

        .logo-left {
            text-align: left;
        }

        .logo-right {
            text-align: right;
        }

        h1 {
            font-size: 22pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .tournament-details {
            font-size: 11pt;
            color: #333;
        }

        .info-section {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 25px;
        }

        .info-section h2 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-item {
            display: table-row;
        }

        .info-label, .info-value {
            display: table-cell;
            padding: 5px 0;
        }

        .info-label {
            font-weight: bold;
            width: 40%;
        }

        .info-value {
            width: 60%;
        }

        .teams-section {
            margin-top: 20px;
        }

        .section-title {
            font-size: 16pt;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        .team-block {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .team-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .team-info {
            font-size: 10pt;
            margin-bottom: 10px;
            padding-left: 10px;
        }

        .players-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #000;
        }

        .players-table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #000;
            background: #f0f0f0;
        }

        .players-table td {
            padding: 6px 8px;
            border: 1px solid #000;
        }

        .jersey-number {
            font-weight: bold;
            text-align: center;
        }

        .no-players {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #666;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 9pt;
        }

        .page-break {
            page-break-after: always;
        }

        @media screen {
            body {
                max-width: 900px;
                margin: 0 auto;
                background: #f5f5f5;
                padding: 20px;
            }

            .page-header {
                background: #fff;
            }

            .download-button-container {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
            }

            .download-btn {
                background: #000;
                color: #fff;
                padding: 15px 30px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
                box-shadow: 0 4px 6px rgba(0,0,0,0.3);
                display: inline-block;
            }

            .download-btn:hover {
                background: #333;
            }
        }

        @media print {
            .download-button-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Download Button (only shows in preview, not in PDF) -->
    @if(!$isPdf)
    <div class="download-button-container">
        <a href="{{ route('tournaments.info.download', $tournament->id) }}" class="download-btn">
            📥 Download PDF
        </a>
    </div>
    @endif

    <!-- Header -->
    <div class="page-header">
        <div class="logo-container">
            <div class="logo-left">
                @if($logoLeft)
                    <img src="data:image/png;base64,{{ $logoLeft }}" alt="Logo">
                @endif
            </div>
            <div class="logo-center">
                <h1>{{ $tournament->name }}</h1>
                <div class="tournament-details">
                    Tournament Information
                </div>
            </div>
            <div class="logo-right">
                @if($logoRight)
                    <img src="data:image/png;base64,{{ $logoRight }}" alt="Logo">
                @endif
            </div>
        </div>
    </div>

    <!-- Tournament Information -->
    <div class="info-section">
        <h2>Tournament Details</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Tournament Name:</div>
                <div class="info-value">{{ $tournament->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Sport:</div>
                <div class="info-value">{{ $tournament->sport->sports_name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Division:</div>
                <div class="info-value">{{ $tournament->division }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Start Date:</div>
                <div class="info-value">
                    {{ $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('F j, Y') : 'TBD' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Teams Registered:</div>
                <div class="info-value"><strong>{{ $tournament->teams->count() }}</strong></div>
            </div>
        </div>
    </div>

    <!-- Teams Section -->
    <div class="teams-section">
        <h2 class="section-title">Teams & Players</h2>

        @if($tournament->teams->count() > 0)
            @foreach($tournament->teams as $team)
                <div class="team-block">
                    <div class="team-name">{{ $team->team_name }}</div>
                    <div class="team-info">
                        <strong>Coach:</strong> {{ $team->coach_name ?? 'Not Assigned' }} | 
                        <strong>Total Players:</strong> {{ $team->players->count() }}
                    </div>

                    @if($team->players->count() > 0)
                        <table class="players-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 15%;">Jersey</th>
                                    <th style="width: 45%;">Player Name</th>
                                    <th style="width: 30%;">Position</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($team->players as $playerIndex => $player)
                                    <tr>
                                        <td style="text-align: center;">{{ $playerIndex + 1 }}</td>
                                        <td class="jersey-number">{{ $player->number ?? '-' }}</td>
                                        <td>{{ $player->name }}</td>
                                        <td>{{ $player->position ?? 'Not Specified' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-players">No players registered for this team</div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="no-players">No teams registered for this tournament</div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Generated on {{ now()->format('F j, Y g:i A') }}</p>
        <p>DigiTally - Digital Scoring & Tournament Management System</p>
    </div>
</body>
</html>