<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Volleyball - {{ $game->team1->team_name }} vs {{ $game->team2->team_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #1a1a1a;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Hamburger Menu Styles */
        .menu-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 10000;
        }

        /* Team Jerseys Display */
.team-jerseys {
    display: flex;
    gap: 4px;
    align-items: center;
    padding: 4px 8px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 6px;
    min-width: 160px;
    justify-content: center;
}

.team-section.right .team-jerseys {
    order: -1; /* Place jerseys before score for right team */
}

.jersey-badge {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #2d2d2d, #3d3d3d);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
    color: white;
    border: 2px solid #444;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.jersey-badge:hover {
    transform: scale(1.1);
    border-color: #4CAF50;
}

.jersey-badge.team-a {
    background: linear-gradient(135deg, #c33, #a22);
    border-color: #c33;
}

.jersey-badge.team-b {
    background: linear-gradient(135deg, #339, #227);
    border-color: #339;
}

.jersey-badge.active-player {
    box-shadow: 0 0 12px rgba(76, 175, 80, 0.6);
    border-color: #4CAF50;
    animation: activeGlow 2s infinite;
}

.jersey-badge.current-server {
    box-shadow: 0 0 14px rgba(255, 235, 59, 0.9);
    border-color: #FFEB3B;
    transform: scale(1.08);
}

@keyframes activeGlow {
    0%, 100% {
        box-shadow: 0 0 12px rgba(76, 175, 80, 0.6);
    }
    50% {
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.9);
    }
}

.jersey-badge .player-number {
    font-size: 14px;
    font-weight: 700;
}

/* Substitution Modal Styles */
.substitution-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.substitution-content {
    background: #2d2d2d;
    border-radius: 16px;
    padding: 40px;
    max-width: 1000px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
}

.sub-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.sub-title {
    font-size: 28px;
    font-weight: bold;
    color: white;
}

.sub-close {
    background: #666;
    border: none;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 20px;
    font-weight: bold;
}

.sub-close:hover {
    background: #777;
}

.sub-instructions {
    background: rgba(76, 175, 80, 0.1);
    border: 2px solid #4CAF50;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 25px;
    text-align: center;
    color: #ddd;
    font-size: 15px;
}

.sub-teams {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.sub-team-section {
    background: #3d3d3d;
    padding: 25px;
    border-radius: 12px;
}

.sub-team-title {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
    padding: 12px;
    border-radius: 8px;
    color: white;
}

.sub-team-a .sub-team-title {
    background: linear-gradient(135deg, #c33, #a22);
}

.sub-team-b .sub-team-title {
    background: linear-gradient(135deg, #339, #227);
}

.sub-section-title {
    font-size: 14px;
    font-weight: 600;
    color: #ccc;
    margin: 20px 0 12px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sub-players-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}

.sub-player-card {
    background: #4d4d4d;
    padding: 16px 12px;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    user-select: none;
}

.sub-player-card:hover {
    background: #5d5d5d;
    transform: translateY(-2px);
}

.sub-player-card.active-player {
    border-color: #4CAF50;
    box-shadow: 0 0 12px rgba(76, 175, 80, 0.3);
}

.sub-player-card.bench-player {
    border-color: #FF9800;
    box-shadow: 0 0 12px rgba(255, 152, 0, 0.2);
}

.sub-player-card.dragging {
    opacity: 0.6;
    transform: scale(0.95) rotate(5deg);
}

.sub-player-card.drag-over {
    background: #6d6d6d;
    border-color: #4CAF50;
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
}

.sub-player-number {
    font-size: 20px;
    font-weight: bold;
    color: white;
    margin-bottom: 4px;
}

.sub-player-position {
    font-size: 12px;
    color: #aaa;
    margin-top: 4px;
}

.sub-player-status {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 4px;
    margin-top: 6px;
    text-transform: uppercase;
    font-weight: 600;
}

.sub-player-status.active {
    background: #4CAF50;
    color: white;
}

.sub-player-status.bench {
    background: #FF9800;
    color: white;
}

/* Add substitution button style */
.btn-substitution {
    background: linear-gradient(135deg, #795548 0%, #5D4037 100%);
}

        .hamburger-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
            transition: all 0.3s;
        }

        .hamburger-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
        }

        .hamburger-btn span {
            width: 25px;
            height: 3px;
            background: white;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .hamburger-btn.active span:nth-child(1) {
            transform: rotate(45deg) translate(7px, 7px);
        }

        .hamburger-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger-btn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }

        .menu-dropdown {
            position: absolute;
            top: 60px;
            left: 0;
            background: #2d2d2d;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.5);
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            min-width: 240px;
            border: 1px solid #444;
        }

        .menu-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
            border-bottom: 1px solid #3d3d3d;
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-item:hover {
            background: #3d3d3d;
            padding-left: 25px;
        }

        .menu-item-icon {
            font-size: 22px;
            width: 28px;
            text-align: center;
        }

        .menu-item-text {
            font-size: 15px;
            font-weight: 600;
        }

        /* Scoreboard */
      .scoreboard {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    padding: 12px 20px 12px 80px;  /* ✅ CHANGED: added left padding */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

        .team-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .team-section.left {
            justify-content: flex-start;
        }

        .team-section.right {
            justify-content: flex-end;
            flex-direction: row-reverse;
        }

        .team-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .team-name {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .team-stats {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: #ccc;
        }

        .score-display {
            font-size: 48px;
            font-weight: bold;
            color: #4CAF50;
            min-width: 80px;
            text-align: center;
            font-family: 'Courier New', monospace;
        }

        .serving-indicator {
    width: 20px;
    height: 20px;
    background: #FF9800;
    border-radius: 50%;
    animation: servePulse 2s infinite;
    flex-shrink: 0;
    margin-left: 12px;
}

        @keyframes servePulse {
            0%, 100% { opacity: 1; box-shadow: 0 0 8px rgba(255, 152, 0, 0.6); }
            50% { opacity: 0.6; box-shadow: 0 0 15px rgba(255, 152, 0, 0.8); }
        }

        .center-panel {
            text-align: center;
            flex: 1;
        }

        .set-display {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .sets-won {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            margin-top: 8px;
        }

        .sets-score {
            font-size: 32px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }

        .timeout-timer {
            background: #FF9800;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 8px;
            display: none;
            animation: pulse 1s infinite;
        }

        .timeout-timer.active {
            display: inline-block;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Main Layout */
        .container {
            display: grid;
            grid-template-columns: 400px 1fr 400px;
            gap: 1px;
            flex: 1;
            overflow: hidden;
            min-height: 0;
        }

        /* Roster Sections */
        .roster-section {
            background: #2d2d2d;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .roster-header {
            padding: 16px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .roster-header.team-a {
            background: #c33;
        }

        .roster-header.team-b {
            background: #339;
        }

        .players-grid {
            display: grid;
            /* 3 columns on wide screens */
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 16px;
            overflow-y: auto;
            min-height: 0;
            
        }

        /* Responsive fallbacks: 2 columns on medium, 1 column on small */
        @media (max-width: 900px) {
            .players-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .players-grid {
                grid-template-columns: 1fr;
            }
        }

       .player-card {
            background: #3d3d3d;
            border-radius: 10px;
            padding: 16px 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            min-height: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;  
        }

        .player-card.current-server {
            outline: 3px solid #FFEB3B;
            transform: scale(1.03);
            box-shadow: 0 8px 30px rgba(255,235,59,0.08);
        }

        .set-server-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(255,235,59,0.95);
    color: #111;
    border: none;
    border-radius: 6px;
    padding: 4px 6px;
    font-weight: 700;
    cursor: pointer;
    font-size: 12px;
    display: inline-block;
}

.player-card .set-server-btn {
    display: inline-block;
}

        .player-card:hover {
            background: #4d4d4d;
            transform: translateY(-2px);
        }

        .player-card.team-a {
            border-left: 4px solid #c33;
        }

        .player-card.team-b {
            border-left: 4px solid #339;
        }

        .player-card.selecting {
            animation: selectPulse 1s infinite;
        }

        /* Drag-and-drop styles for roster reordering */
        .player-card.dragging {
            opacity: 0.5;
            transform: scale(0.98);
            box-shadow: 0 8px 24px rgba(0,0,0,0.6);
        }

        .player-card.drag-over {
            outline: 3px dashed rgba(76,175,80,0.7);
            transform: translateY(-4px) scale(1.02);
        }

        @keyframes selectPulse {
            0%, 100% { box-shadow: 0 0 0 2px #4CAF50; }
            50% { box-shadow: 0 0 0 4px #4CAF50; }
        }

        .player-number {
            font-size: 20px;
            font-weight: bold;
        }

        .player-position {
            font-size: 12px;
            color: #aaa;
            margin-top: 4px;
        }

        /* Event Log */
        .log-section {
            background: #1e1e1e;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .log-header {
            padding: 15px 20px;
            background: #333;
            border-bottom: 1px solid #444;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            flex-shrink: 0;
        }

        .log-content {
            flex: 1;
            overflow-y: auto;
            padding: 0;
        }

        .log-entry {
            display: grid;
            grid-template-columns: 40px 50px 60px 1fr 60px 80px 40px;
            align-items: center;
            padding: 14px 15px;
            border-bottom: 1px solid #333;
            font-size: 12px;
            transition: background 0.2s;
            gap: 8px;
        }

        .log-entry:hover {
            background: #2a2a2a;
        }

        .log-entry.team-a {
            border-left: 3px solid #c33;
        }

        .log-entry.team-b {
            border-left: 3px solid #339;
        }

        .entry-number {
            color: #888;
            font-size: 11px;
        }

        .entry-team {
            font-weight: bold;
            font-size: 14px;
        }

        .entry-player {
            font-weight: 600;
        }

        .entry-action {
            color: #ddd;
        }

        .entry-set {
            color: #888;
            font-size: 11px;
        }

        .entry-score {
            color: #888;
            font-size: 11px;
            font-family: 'Courier New', monospace;
        }

        .entry-check {
            color: #4CAF50;
            font-size: 16px;
        }

         /* ✅ ADD: Block Type Modal Styles */
        .block-type-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }

        .block-type-modal.show {
            display: flex;
        }

        .block-type-content {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
        }

        .block-type-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #9C27B0;
        }

        .block-type-subtitle {
            font-size: 16px;
            color: #aaa;
            margin-bottom: 30px;
        }

        .block-type-options {
            display: grid;
            gap: 15px;
            margin-bottom: 20px;
        }

        .block-type-btn {
            padding: 20px;
            font-size: 18px;
            font-weight: bold;
            border: 3px solid transparent;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .block-type-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .btn-kill-block {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .btn-kill-block:hover {
            border-color: #4CAF50;
        }

        .btn-regular-block {
            background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
            color: white;
        }

        .btn-regular-block:hover {
            border-color: #9C27B0;
        }

        .btn-yellow-card {
            background: linear-gradient(135deg, #FDD835 0%, #F9A825 100%);
            color: #000;
            font-weight: 800;
        }

        .btn-red-card {
            background: linear-gradient(135deg, #E53935 0%, #C62828 100%);
            color: white;
            font-weight: 800;
        }

        /* Action Buttons */
        .actions-section {
            background: #2d2d2d;
            padding: 16px;
            border-top: 1px solid #444;
            flex-shrink: 0;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(9, 1fr);
            gap: 12px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .action-btn {
            padding: 16px 12px;
            font-size: 13px;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .action-btn.selected {
            outline: 3px solid #4CAF50;
            outline-offset: 2px;
        }

        .btn-kill {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        }

        .btn-ace {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        }

        .btn-block {
            background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
        }

        .btn-dig {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
        }

        .btn-assist {
            background: linear-gradient(135deg, #3F51B5 0%, #303F9F 100%);
        }

        .btn-error {
            background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
        }

        .btn-timeout {
            background: linear-gradient(135deg, #607D8B 0%, #455A64 100%);
        }

        .btn-undo {
            background: linear-gradient(135deg, #666 0%, #555 100%);
        }

        .btn-penalty {
    background: linear-gradient(135deg, #FFC107 0%, #FFA000 100%);
    color: #000;
    font-weight: 800;
}

/* Penalty Cards Modal */
.penalty-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 10000;
}

.penalty-modal.show {
    display: flex;
}

.penalty-content {
    background: #2d2d2d;
    border-radius: 16px;
    padding: 40px;
    max-width: 700px;
    width: 90%;
}

.penalty-title {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 10px;
    text-align: center;
    color: #FFC107;
}

.penalty-subtitle {
    font-size: 16px;
    color: #aaa;
    margin-bottom: 30px;
    text-align: center;
}

.penalty-cards-section {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-bottom: 40px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
}

.penalty-card {
    width: 120px;
    height: 160px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: grab;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    user-select: none;
}

.penalty-card:active {
    cursor: grabbing;
}

.penalty-card.dragging {
    opacity: 0.5;
    transform: scale(0.95);
}

.penalty-card.yellow-card {
    background: linear-gradient(135deg, #FDD835 0%, #F9A825 100%);
    color: #000;
}

.penalty-card.red-card {
    background: linear-gradient(135deg, #E53935 0%, #C62828 100%);
    color: white;
}

.penalty-card:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
}

.card-icon {
    font-size: 48px;
    margin-bottom: 10px;
}

.card-label {
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
}

.card-description {
    font-size: 11px;
    margin-top: 8px;
    opacity: 0.8;
    text-align: center;
    padding: 0 10px;
}

.penalty-teams-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.penalty-team-zone {
    background: #3d3d3d;
    border: 3px dashed #555;
    border-radius: 12px;
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s;
    min-height: 150px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.penalty-team-zone.drag-over {
    border-color: #FFC107;
    background: rgba(255, 193, 7, 0.1);
    transform: scale(1.02);
}

.penalty-team-zone.team-a {
    border-color: #c33;
}

.penalty-team-zone.team-b {
    border-color: #339;
}

.penalty-team-zone.drag-over.team-a {
    border-color: #FFC107;
    background: rgba(204, 51, 51, 0.1);
}

.penalty-team-zone.drag-over.team-b {
    border-color: #FFC107;
    background: rgba(51, 51, 153, 0.1);
}

.penalty-team-name {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.penalty-team-zone.team-a .penalty-team-name {
    color: #c33;
}

.penalty-team-zone.team-b .penalty-team-name {
    color: #339;
}

.drop-instruction {
    font-size: 14px;
    color: #888;
}

        /* Modals */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 40px;
            max-width: 600px;
            width: 90%;
            text-align: center;
        }

        .modal-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 24px;
        }

        .modal-subtitle {
            font-size: 18px;
            color: #aaa;
            margin-bottom: 32px;
        }

        .modal-score {
            display: flex;
            justify-content: space-around;
            margin: 32px 0;
        }

        .modal-team {
            text-align: center;
        }

        .modal-team-name {
            font-size: 14px;
            color: #888;
            margin-bottom: 8px;
        }

        .modal-team-score {
            font-size: 48px;
            font-weight: bold;
        }

        .modal-btn {
            width: 100%;
            padding: 16px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            margin-top: 12px;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .modal-btn-secondary {
            background: #666;
            color: white;
        }

        .modal-btn-warning {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
        }

        /* Team Selection Modal */
        .team-select-btn {
            width: 100%;
            padding: 24px;
            font-size: 20px;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            color: white;
            margin-bottom: 16px;
        }

        .team-select-btn.team-a {
            background: linear-gradient(135deg, #c33 0%, #a22 100%);
        }

        .team-select-btn.team-b {
            background: linear-gradient(135deg, #339 0%, #227 100%);
        }

        .team-select-btn:hover {
            transform: scale(1.02);
        }

        /* Instruction Banner */
        .instruction-banner {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: #4CAF50;
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 14px;
            display: none;
            z-index: 5000;
            animation: fadeIn 0.3s;
        }

        .instruction-banner.show {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(-50%) translateY(10px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }

        /* Set Score Display */
        .set-scores {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 8px;
        }

        .set-score-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-family: 'Courier New', monospace;
        }

        .set-score-box.won-a {
            background: rgba(204, 51, 51, 0.3);
        }

        .set-score-box.won-b {
            background: rgba(51, 51, 153, 0.3);
        }

        /* Hotkeys Modal Styles */
.hotkey-item {
    background: #3d3d3d;
    padding: 15px;
    border-radius: 8px;
}

.hotkey-input {
    background: #4d4d4d;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
    text-align: center;
}

.hotkey-input:hover {
    background: #5d5d5d;
    border-color: #4CAF50;
}

.hotkey-input.listening {
    background: #FF9800;
    border-color: #F57C00;
    animation: pulse 1s infinite;
}

.current-key {
    color: white;
    font-weight: bold;
    font-size: 16px;
    font-family: 'Courier New', monospace;
}

/* Settings Input Styles */
.settings-input {
    background: #4d4d4d;
    color: white;
    padding: 10px;
    border-radius: 6px;
    border: none;
    font-size: 16px;
    width: 100%;
}

.team-jerseys {
    display: none !important;
}
    </style>
</head>
<body>
    <!-- Hamburger Menu -->
    <div class="menu-container">
        <button class="hamburger-btn" id="hamburgerBtn">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="menu-dropdown" id="menuDropdown">
            <a href="/tournaments/{{ $game->bracket->tournament_id ?? '' }}" class="menu-item">
                <span class="menu-item-icon">🏆</span>
                <span class="menu-item-text">Back to Tournament</span>
            </a>
            <a href="/games/{{ $game->id }}/volleyball-scoresheet" class="menu-item">
                <span class="menu-item-icon">📄</span>
                <span class="menu-item-text">View Scoresheet</span>
            </a>
            <a href="#" class="menu-item" id="hotkeysBtn">
            <span class="menu-item-icon">⌨️</span>
            <span class="menu-item-text">Customize Hotkeys</span>
        </a>
        <a href="#" class="menu-item" id="gameSettingsBtn">
            <span class="menu-item-icon">⚙️</span>
            <span class="menu-item-text">Game Settings</span>
        </a>
        </div>
    </div>

    <!-- Instruction Banner -->
    <div class="instruction-banner" id="instructionBanner"></div>

    <!-- Scoreboard -->
<div class="scoreboard">
    <!-- Team A Section -->
    <div class="team-section left">
        <div class="team-info">
            <div class="team-name" id="teamAName">{{ strtoupper($game->team1->team_name) }}</div>
            <div class="team-stats">
                <span>T.O: <span id="timeoutsA">0</span>/<span id="maxTimeoutsA">2</span></span>
                <span>SUB: <span id="substitutionsA">0</span>/<span id="maxSubstitutionsA">6</span></span>
            </div>
        </div>
        <!-- Team A Player Jerseys -->
        <div class="team-jerseys" id="teamAJerseys">
            <!-- Will be populated by JavaScript -->
        </div>
        <div class="score-display" id="scoreA">00</div>
        <div class="serving-indicator" id="servingA" style="display: none;"></div>
    </div>

    <!-- Center Panel -->
    <div class="center-panel">
        <div class="set-display">SET <span id="currentSet">1</span></div>
        <div class="sets-won">
            <span class="sets-score" id="setsA">0</span>
            <span style="color: #666;">-</span>
            <span class="sets-score" id="setsB">0</span>
        </div>
        <div class="set-scores" id="setScoresDisplay"></div>
        <div class="timeout-timer" id="timeoutTimer">TIMEOUT: <span id="timeoutTime">30</span>s</div>
    </div>

    <!-- Team B Section -->
    <div class="team-section right">
        <div class="serving-indicator" id="servingB" style="display: none;"></div>
        <div class="score-display" id="scoreB">00</div>
        <!-- Team B Player Jerseys -->
        <div class="team-jerseys" id="teamBJerseys">
            <!-- Will be populated by JavaScript -->
        </div>
        <div class="team-info">
            <div class="team-name" id="teamBName">{{ strtoupper($game->team2->team_name) }}</div>
            <div class="team-stats">
                <span>T.O: <span id="timeoutsB">0</span>/<span id="maxTimeoutsB">2</span></span>
                <span>SUB: <span id="substitutionsB">0</span>/<span id="maxSubstitutionsB">6</span></span>
            </div>
        </div>
    </div>
</div>

    <!-- Main Content -->
    <div class="container">
        <!-- Team A Roster -->
        <div class="roster-section">
            <div class="roster-header team-a">{{ strtoupper($game->team1->team_name) }}</div>
            <div class="players-grid" id="playersA"></div>
        </div>

        <!-- Event Log -->
        <div class="log-section">
            <div class="log-header">GAME EVENTS</div>
            <div class="log-content" id="logContent"></div>
        </div>

        <!-- Team B Roster -->
        <div class="roster-section">
            <div class="roster-header team-b">{{ strtoupper($game->team2->team_name) }}</div>
            <div class="players-grid" id="playersB"></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="actions-section">
    <div class="actions-grid">
       <button class="action-btn btn-kill" data-action="kill">Kill</button>
        <button class="action-btn btn-ace" data-action="ace">Ace</button>
        <button class="action-btn btn-block" id="blockBtn">Block</button>
        <button class="action-btn btn-dig" data-action="dig">Dig</button>
        <button class="action-btn btn-assist" data-action="assist">Set</button>
        <button class="action-btn btn-error" data-action="error">Error</button>
        <button class="action-btn btn-penalty" id="penaltyBtn">🃏 Penalty</button>
        <button class="action-btn btn-timeout" id="timeoutBtn">Timeout</button>
        <button class="action-btn btn-substitution" id="substitutionBtn">Sub</button>
        <button class="action-btn btn-undo" id="undoBtn">↶ Undo</button>
    </div>
</div>

    <!-- Team Selection Modal -->
    <div class="modal" id="teamSelectModal">
        <div class="modal-content">
            <h2 class="modal-title">Select Team</h2>
            <p class="modal-subtitle" id="teamSelectPrompt">Choose which team</p>
            <button class="team-select-btn team-a" data-team="A" id="selectTeamA">{{ strtoupper($game->team1->team_name) }}</button>
            <button class="team-select-btn team-b" data-team="B" id="selectTeamB">{{ strtoupper($game->team2->team_name) }}</button>
            <button class="modal-btn modal-btn-secondary" onclick="closeTeamSelectModal()">Cancel</button>
        </div>
    </div>

    <!-- Set End Modal -->
    <div class="modal" id="setEndModal">
        <div class="modal-content" style="border: 4px solid #4CAF50;">
            <h2 class="modal-title" style="color: #4CAF50;">SET <span id="endedSetNumber">1</span> ENDED</h2>
            <div class="modal-score">
                <div class="modal-team">
                    <div class="modal-team-name" id="setEndTeamA"></div>
                    <div class="modal-team-score" id="setEndScoreA">00</div>
                </div>
                <div class="modal-team">
                    <div class="modal-team-name" id="setEndTeamB"></div>
                    <div class="modal-team-score" id="setEndScoreB">00</div>
                </div>
            </div>
            <div style="margin: 20px 0;">
                <div style="color: #888; margin-bottom: 8px;">SETS WON</div>
                <div style="font-size: 32px; font-weight: bold;"><span id="modalSetsA">0</span> - <span id="modalSetsB">0</span></div>
            </div>
            <button class="modal-btn modal-btn-primary" onclick="startNextSet()">START SET <span id="nextSetNumber">2</span></button>
        </div>
    </div>

    <!-- Game End Modal -->
    <div class="modal" id="gameEndModal">
        <div class="modal-content" style="border: 4px solid #FF9800;">
            <h2 class="modal-title" style="color: #FF9800;">GAME ENDED</h2>
            <p class="modal-subtitle" style="font-size: 24px; color: white;" id="winnerText"></p>
            <div style="font-size: 40px; font-weight: bold; margin: 20px 0;">
                <span id="finalSetsA">0</span> - <span id="finalSetsB">0</span>
            </div>
            <div style="margin: 20px 0; text-align: left; max-height: 200px; overflow-y: auto;">
                <div style="color: #888; margin-bottom: 12px; text-align: center;">SET SCORES</div>
                <div id="finalSetScores"></div>
            </div>
            <button class="modal-btn modal-btn-warning" onclick="saveGameResults()">SAVE GAME RESULTS</button>
        </div>
    </div>

    <!-- Hotkeys Modal -->
<div class="modal" id="hotkeysModal">
    <div class="modal-content">
        <h2 class="modal-title">Customize Hotkeys</h2>
        <p class="modal-subtitle">Click on an action and press a key to assign a hotkey. Press ESC to clear.</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin: 20px 0;">
            <div class="hotkey-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Kill</div>
                <div class="hotkey-input" data-action="kill">
                    <span class="current-key" id="key-kill">K</span>
                </div>
            </div>
            
            <div class="hotkey-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Ace</div>
                <div class="hotkey-input" data-action="ace">
                    <span class="current-key" id="key-ace">A</span>
                </div>
            </div>
            
            <div class="hotkey-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Block</div>
                <div class="hotkey-input" data-action="block">
                    <span class="current-key" id="key-block">B</span>
                </div>
            </div>
            
            <div class="hotkey-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Dig</div>
                <div class="hotkey-input" data-action="dig">
                    <span class="current-key" id="key-dig">D</span>
                </div>
            </div>
            
            <div class="hotkey-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Set</div>
                <div class="hotkey-input" data-action="assist">
                    <span class="current-key" id="key-assist">S</span>
                </div>
            </div>
            
            <div class="hotkey-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Error</div>
                <div class="hotkey-input" data-action="error">
                    <span class="current-key" id="key-error">E</span>
                </div>
            </div>
            
            <div class="hotkey-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Timeout</div>
                <div class="hotkey-input" data-action="timeout">
                    <span class="current-key" id="key-timeout">T</span>
                </div>
            </div>
            
            <div class="hotkey-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Undo</div>
                <div class="hotkey-input" data-action="undo">
                    <span class="current-key" id="key-undo">Z</span>
                </div>
            </div>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
            <button class="modal-btn modal-btn-secondary" onclick="resetHotkeysToDefault()">Reset to Defaults</button>
            <button class="modal-btn modal-btn-primary" onclick="saveHotkeysSettings()">Save Changes</button>
            <button class="modal-btn modal-btn-secondary" onclick="closeHotkeysModal()">Cancel</button>
        </div>
    </div>
</div>

<!-- Game Settings Modal -->
<div class="modal" id="gameSettingsModal">
    <div class="modal-content">
        <h2 class="modal-title">Game Settings</h2>
        <p class="modal-subtitle">Customize limits per set per team</p>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 20px 0;">
            <div class="settings-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Team A Max Timeouts</div>
                <input type="number" min="0" class="settings-input" id="maxTimeoutsAInput">
            </div>
            
            <div class="settings-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Team B Max Timeouts</div>
                <input type="number" min="0" class="settings-input" id="maxTimeoutsBInput">
            </div>
            
            <div class="settings-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Team A Max Substitutions</div>
                <input type="number" min="0" class="settings-input" id="maxSubstitutionsAInput">
            </div>
            
            <div class="settings-item">
                <div style="color: white; font-weight: 600; margin-bottom: 8px;">Team B Max Substitutions</div>
                <input type="number" min="0" class="settings-input" id="maxSubstitutionsBInput">
            </div>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
            <button class="modal-btn modal-btn-secondary" onclick="resetSettingsToDefault()">Reset to Defaults</button>
            <button class="modal-btn modal-btn-primary" onclick="saveGameSettings()">Save Changes</button>
            <button class="modal-btn modal-btn-secondary" onclick="closeGameSettingsModal()">Cancel</button>
        </div>
    </div>
</div>

<!-- Substitution Modal -->
<div class="substitution-modal" id="substitutionModal">
    <div class="substitution-content">
        <div class="sub-header">
            <div class="sub-title">Player Substitutions</div>
            <button class="sub-close" id="subClose">&times;</button>
        </div>

        <div class="sub-instructions">
            Drag a bench player onto an active player to make a substitution
        </div>

        <div class="sub-teams">
            <!-- Team A Substitutions -->
            <div class="sub-team-section sub-team-a">
                <div class="sub-team-title">{{ strtoupper($game->team1->team_name) }}</div>

                <div class="sub-section-title">Active Players (On Court)</div>
                <div class="sub-players-grid" id="activePlayersA"></div>

                <div class="sub-section-title">Bench Players</div>
                <div class="sub-players-grid" id="benchPlayersA"></div>
            </div>

            <!-- Team B Substitutions -->
            <div class="sub-team-section sub-team-b">
                <div class="sub-team-title">{{ strtoupper($game->team2->team_name) }}</div>

                <div class="sub-section-title">Active Players (On Court)</div>
                <div class="sub-players-grid" id="activePlayersB"></div>

                <div class="sub-section-title">Bench Players</div>
                <div class="sub-players-grid" id="benchPlayersB"></div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ NEW: Block Type Selection Modal -->
    <div class="block-type-modal" id="blockTypeModal">
        <div class="block-type-content">
            <div class="block-type-title">🏐 Select Block Type</div>
            <div class="block-type-subtitle">Choose the type of block</div>
            
            <div class="block-type-options">
                <button class="block-type-btn btn-kill-block" onclick="handleBlockType('kill_block')">
                    <span>⚡</span>
                    <div>
                        <div>KILL BLOCK</div>
                        <div style="font-size: 12px; opacity: 0.8;">Point scored + Block recorded</div>
                    </div>
                </button>
                
                <button class="block-type-btn btn-regular-block" onclick="handleBlockType('regular_block')">
                    <span>🛡️</span>
                    <div>
                        <div>REGULAR BLOCK</div>
                        <div style="font-size: 12px; opacity: 0.8;">Block recorded only (no point)</div>
                    </div>
                </button>
            </div>

            <button class="modal-btn modal-btn-secondary" onclick="closeBlockTypeModal()">Cancel</button>
        </div>
    </div>

    <div class="penalty-modal" id="penaltyModal">
    <div class="penalty-content">
        <div class="penalty-title">🃏 Issue Penalty Card</div>
        <div class="penalty-subtitle">Drag a card to the team receiving the penalty</div>
        
        <!-- Draggable Cards Section -->
        <div class="penalty-cards-section">
            <div class="penalty-card yellow-card" draggable="true" id="yellowCard">
                <div class="card-icon">⚠️</div>
                <div class="card-label">Yellow</div>
                <div class="card-description">Warning</div>
            </div>
            
            <div class="penalty-card red-card" draggable="true" id="redCard">
                <div class="card-icon">🟥</div>
                <div class="card-label">Red</div>
                <div class="card-description">Opponent scores</div>
            </div>
        </div>

        <!-- Team Drop Zones -->
        <div class="penalty-teams-section">
            <div class="penalty-team-zone team-a" id="penaltyZoneA" data-team="A">
                <div class="penalty-team-name">{{ strtoupper($game->team1->team_name) }}</div>
                <div class="drop-instruction">Drop card here</div>
            </div>

            <div class="penalty-team-zone team-b" id="penaltyZoneB" data-team="B">
                <div class="penalty-team-name">{{ strtoupper($game->team2->team_name) }}</div>
                <div class="drop-instruction">Drop card here</div>
            </div>
        </div>

        <button class="modal-btn modal-btn-secondary" onclick="closePenaltyModal()">Cancel</button>
    </div>
</div>

    <script>
        // Game data from Laravel
        const gameData = {
            id: {{ $game->id }},
            team1: {
                name: '{{ $game->team1->team_name }}',
                players: @json($team1Players->values())
            },
            team2: {
                name: '{{ $game->team2->team_name }}',
                players: @json($team2Players->values())
            }
        };

        // Game state
        let scoreA = 0, scoreB = 0;
        let currentSet = 1;
        let setsA = 0, setsB = 0;
        let serving = 'A';
        let timeoutsA = 0, timeoutsB = 0;
        let substitutionsA = 0, substitutionsB = 0;
        let events = [];
        let eventCounter = 1;
       
        let setScores = {
            A: [0, 0, 0, 0, 0],
            B: [0, 0, 0, 0, 0]
        };

        let selectedAction = null;
        let selectingPlayer = false;
        let selectingTeam = false;
        let teamSelectCallback = null;
    // Current server tracking
    let currentServerId = null;
    let currentServerTeam = null;

        let timeoutActive = false;
        let timeoutTime = 30;
        let timeoutInterval = null;

        // Game settings limits
        let maxTimeoutsA = 2;
        let maxTimeoutsB = 2;
        let maxSubstitutionsA = 6;
        let maxSubstitutionsB = 6;

        // Hamburger menu functionality
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const menuDropdown = document.getElementById('menuDropdown');

        hamburgerBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            hamburgerBtn.classList.toggle('active');
            menuDropdown.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.menu-container')) {
                hamburgerBtn.classList.remove('active');
                menuDropdown.classList.remove('show');
            }
        });

        // Player roster management
let activePlayers = {
    A: [],
    B: []
};

let benchPlayers = {
    A: [],
    B: []
};

// Initialize player rosters from game data
function initializePlayerRosters() {
    // Team A - first 6 players are active (starters)
    activePlayers.A = gameData.team1.players.slice(0, 6);
    benchPlayers.A = gameData.team1.players.slice(6);

    // Team B - first 6 players are active (starters)
    activePlayers.B = gameData.team2.players.slice(0, 6);
    benchPlayers.B = gameData.team2.players.slice(6);

    console.log('Initialized rosters:', { activePlayers, benchPlayers });
    
    // Render jerseys in scoreboard
    renderTeamJerseys();
}

// Render team jerseys in scoreboard
function renderTeamJerseys() {
    const teamAJerseys = document.getElementById('teamAJerseys');
    const teamBJerseys = document.getElementById('teamBJerseys');

    teamAJerseys.innerHTML = '';
    teamBJerseys.innerHTML = '';

    // Render Team A jerseys
    activePlayers.A.forEach(player => {
        const badge = createJerseyBadge(player, 'A');
        teamAJerseys.appendChild(badge);
    });

    // Render Team B jerseys
    activePlayers.B.forEach(player => {
        const badge = createJerseyBadge(player, 'B');
        teamBJerseys.appendChild(badge);
    });
}

// Create jersey badge
function createJerseyBadge(player, team) {
    const badge = document.createElement('div');
    badge.className = `jersey-badge team-${team.toLowerCase()} active-player`;
    badge.dataset.playerId = player.id;
    badge.dataset.team = team;
    badge.title = player.name || `#${player.number}`;
    
    badge.innerHTML = `<span class="player-number">${player.number || '00'}</span>`;
    
    // clicking a jersey sets that player as the current server for their team
    badge.addEventListener('click', (e) => {
        e.stopPropagation();
        setServer(team, player.id);
    });

    // visually mark current server
    if (currentServerId && currentServerId.toString() === player.id.toString() && currentServerTeam === team) {
        badge.classList.add('current-server');
    }

    return badge;
}

        document.getElementById('blockBtn').addEventListener('click', function() {
            // Show block type modal instead of immediately selecting player
            selectedAction = 'block';
            document.querySelectorAll('.action-btn').forEach(btn => btn.classList.remove('selected'));
            this.classList.add('selected');
            
            showInstruction('Select a team first, then choose block type');
            selectingTeam = true;
            teamSelectCallback = handleBlockTeamSelect;
            showTeamSelectModal('Which team made the block?');
        });

        // ✅ NEW: Handle Block Team Selection
        function handleBlockTeamSelect(team) {
            blockingTeam = team;
            closeTeamSelectModal();
            
            // Now show block type modal
            document.getElementById('blockTypeModal').classList.add('show');
        }

        // ✅ NEW: Handle Block Type Selection
        function handleBlockType(blockType) {
            pendingBlockType = blockType;
            document.getElementById('blockTypeModal').classList.remove('show');
            
            // Now select player
            selectingPlayer = true;
            document.querySelectorAll('.player-card').forEach(card => {
                if (card.dataset.team === blockingTeam) {
                    card.classList.add('selecting');
                }
            });
            
            if (blockType === 'kill_block') {
                showInstruction(`Select ${blockingTeam === 'A' ? gameData.team1.name : gameData.team2.name} player who made the KILL BLOCK (will score point)`);
            } else {
                showInstruction(`Select ${blockingTeam === 'A' ? gameData.team1.name : gameData.team2.name} player who made the REGULAR BLOCK (no point)`);
            }
        }

        // ✅ NEW: Close Block Type Modal
        function closeBlockTypeModal() {
            document.getElementById('blockTypeModal').classList.remove('show');
            blockingTeam = null;
            resetSelection();
        }

        // ✅ UPDATED: Handle Player Click for Blocks
        function handlePlayerClick(team, player) {
            if (!selectingPlayer) return;

            const playerNumber = player.number || '00';
            
            // Handle block action
            if (selectedAction === 'block' && pendingBlockType) {
                if (pendingBlockType === 'kill_block') {
                    // Kill block: award point and record block
                    handleScore(team, 'Kill Block', playerNumber);
                } else {
                    // Regular block: just record the stat
                    logEvent(team, playerNumber, 'Block', 0);
                }
                
                pendingBlockType = null;
                blockingTeam = null;
                resetSelection();
                return;
            }
            
            // Handle other actions (kill, ace, dig, assist)
            if (selectedAction === 'kill' || selectedAction === 'ace' || selectedAction === 'block') {
                handleScore(team, selectedAction, playerNumber);
            } else if (selectedAction === 'dig' || selectedAction === 'assist') {
                logEvent(team, playerNumber, selectedAction.charAt(0).toUpperCase() + selectedAction.slice(1), 0);
            }

            resetSelection();
        }

        


document.getElementById('penaltyBtn').addEventListener('click', function() {
    openPenaltyModal();
});

function openPenaltyModal() {
    document.getElementById('penaltyModal').classList.add('show');
    setupPenaltyCardDragDrop();
}

function closePenaltyModal() {
    document.getElementById('penaltyModal').classList.remove('show');
}

// ✅ NEW: Penalty Card Drag and Drop System
let draggedCard = null;
let draggedCardType = null;

function setupPenaltyCardDragDrop() {
    const yellowCard = document.getElementById('yellowCard');
    const redCard = document.getElementById('redCard');
    const zoneA = document.getElementById('penaltyZoneA');
    const zoneB = document.getElementById('penaltyZoneB');

    // Card drag events
    [yellowCard, redCard].forEach(card => {
        card.addEventListener('dragstart', function(e) {
            draggedCard = this;
            draggedCardType = this.classList.contains('yellow-card') ? 'yellow' : 'red';
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        });

        card.addEventListener('dragend', function(e) {
            this.classList.remove('dragging');
            draggedCard = null;
            draggedCardType = null;
        });
    });

    // Team zone drop events
    [zoneA, zoneB].forEach(zone => {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });

        zone.addEventListener('dragenter', function(e) {
            e.preventDefault();
            if (draggedCard) {
                this.classList.add('drag-over');
            }
        });

        zone.addEventListener('dragleave', function(e) {
            if (!this.contains(e.relatedTarget)) {
                this.classList.remove('drag-over');
            }
        });

        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            
            if (draggedCard && draggedCardType) {
                const team = this.dataset.team;
                handlePenaltyCardDrop(team, draggedCardType);
            }
        });
    });
}

let penaltyProcessing = false;

// STEP 2: Replace handlePenaltyCardDrop with safeguards
function handlePenaltyCardDrop(team, cardType) {
    // ✅ PREVENT MULTIPLE SIMULTANEOUS CALLS
    if (penaltyProcessing) {
        console.warn('Penalty already processing, ignoring duplicate call');
        return;
    }
    
    penaltyProcessing = true;
    console.log('=== PENALTY CARD DROPPED ===');
    console.log('Team:', team, 'Card:', cardType);
    console.log('Score BEFORE - A:', scoreA, 'B:', scoreB);
    
    closePenaltyModal();

    if (cardType === 'yellow') {
        logEvent(team, 'TEAM', '⚠️ Yellow Card (Warning)', 0);
        showNotification(`Yellow card issued to Team ${team} - WARNING`, '#FDD835');
        penaltyProcessing = false;
        return;
    }
    
    if (cardType === 'red') {
        const opponent = team === 'A' ? 'B' : 'A';
        
        // Award exactly 1 point
        if (opponent === 'A') {
            scoreA++;
            console.log('Team A score increased to:', scoreA);
        } else {
            scoreB++;
            console.log('Team B score increased to:', scoreB);
        }
        
        updateScoreDisplay();
        logEvent(team, 'TEAM', '🟥 Red Card (Penalty Point)', 0);
        
        // Only switch serve if needed
        if (opponent !== serving) {
            console.log('Switching serve to', opponent);
            serving = opponent;
            rotateTeamClockwise(opponent);
            
            const serverIndexMap = {1:5, 2:2, 3:1, 4:0, 5:3, 6:4};
            const arr = opponent === 'A' ? activePlayers.A : activePlayers.B;
            const newServer = arr && arr[serverIndexMap[1]] ? arr[serverIndexMap[1]] : null;
            
            if (newServer) {
                currentServerId = newServer.id;
                currentServerTeam = opponent;
            }
            
            updateServingIndicator();
            highlightServerBadge();
            logEvent('GAME', 'SYSTEM', `Serve → Team ${opponent}`, 0);
        }
        
        checkSetWin();
        showNotification(`Red card to Team ${team} - 1 point to Team ${opponent}`, '#E53935');
    }
    
    console.log('FINAL Score - A:', scoreA, 'B:', scoreB);
    console.log('=== PENALTY COMPLETE ===');
    
    // ✅ Reset the flag after a short delay
    setTimeout(() => {
        penaltyProcessing = false;
    }, 500);
}

        // ✅ Helper function for notifications
        function showNotification(message, bgColor = '#4CAF50') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: ${bgColor};
        color: ${bgColor === '#FDD835' ? '#000' : 'white'};
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: bold;
        z-index: 10001;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        animation: fadeIn 0.3s;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (document.body.contains(notification)) {
            document.body.removeChild(notification);
        }
    }, 3000);
}

// Set the current server (choose which team serves and which player)
function setServer(team, playerId) {
    serving = team; // set global serving team
    currentServerTeam = team;

    // Mapping of position -> array index for the grid layout:
    // positions:
    // [4, 3, 2]
    // [5, 6, 1]
    // arr indices: 0->4,1->3,2->2,3->5,4->6,5->1
    const posIndex = {1:5,2:2,3:1,4:0,5:3,6:4};

    const arr = team === 'A' ? activePlayers.A : activePlayers.B;
    if (arr && arr.length >= 1) {
        // Try to rotate the team's active array until the chosen player is in the server position (pos 1)
        let attempts = 0;
        while ((arr[posIndex[1]] && arr[posIndex[1]].id.toString() !== playerId.toString()) && attempts < 6) {
            rotateTeamClockwise(team);
            attempts++;
        }
    }

    currentServerId = playerId;
    updateServingIndicator();
    renderTeamJerseys();
    updateMainRoster();
    renderSubstitutionPlayers();
    highlightServerBadge();
    showNotification(`Server set to Team ${team} player #${playerId}`);
}

function highlightServerBadge() {
    // Clear both jersey badges and roster cards
    document.querySelectorAll('.jersey-badge').forEach(b => b.classList.remove('current-server'));
    document.querySelectorAll('.player-card').forEach(c => c.classList.remove('current-server'));
    if (!currentServerId) return;
    // highlight jersey if visible
    const el = document.querySelector(`.jersey-badge[data-player-id="${currentServerId}"]`);
    if (el) el.classList.add('current-server');
    // highlight roster card
    const card = document.querySelector(`.player-card[data-player-id="${currentServerId}"]`);
    if (card) card.classList.add('current-server');
}

// Rotate a team's active players clockwise (used when they gain serve)
function rotateTeamClockwise(team) {
    // Use the court layout mapping so rotation matches visual positions.
    // positions layout (visual):
    // [4, 3, 2]
    // [5, 6, 1]
    // array indices map: index 0->pos4, 1->pos3, 2->pos2, 3->pos5, 4->pos6, 5->pos1
    const posIndex = {1:5,2:2,3:1,4:0,5:3,6:4};
    const arr = team === 'A' ? activePlayers.A : activePlayers.B;
    if (!arr || arr.length < 6) return; // require full 6 players to rotate

    const old = arr.slice();
    const newArr = new Array(6);
    // For each position p (1..6), new occupant at p becomes the old occupant at p_next (clockwise next)
    // Based on desired mapping: new[pos] = old[nextPos], where nextPos = (pos % 6) + 1
    for (let pos = 1; pos <= 6; pos++) {
        const nextPos = pos % 6 + 1;
        const newIndex = posIndex[pos];
        const oldIndex = posIndex[nextPos];
        newArr[newIndex] = old[oldIndex];
    }

    // copy back into original array
    for (let i = 0; i < 6; i++) arr[i] = newArr[i];

    // After rotation, re-render UI
    renderTeamJerseys();
    updateMainRoster();
    renderSubstitutionPlayers();
}

// Substitution Modal Functions
const substitutionModal = document.getElementById('substitutionModal');
const subCloseBtn = document.getElementById('subClose');
const substitutionBtn = document.getElementById('substitutionBtn');

// Open substitution modal
function openSubstitutionModal() {
    substitutionModal.style.display = 'flex';
    renderSubstitutionPlayers();
}

// Close substitution modal
function closeSubstitutionModal() {
    substitutionModal.style.display = 'none';
}

// Render players in substitution modal
function renderSubstitutionPlayers() {
    const activePlayersA = document.getElementById('activePlayersA');
    const benchPlayersA = document.getElementById('benchPlayersA');
    const activePlayersB = document.getElementById('activePlayersB');
    const benchPlayersB = document.getElementById('benchPlayersB');

    // Clear existing
    activePlayersA.innerHTML = '';
    benchPlayersA.innerHTML = '';
    activePlayersB.innerHTML = '';
    benchPlayersB.innerHTML = '';

    // Render Team A
    activePlayers.A.forEach(player => {
        activePlayersA.appendChild(createSubPlayerCard(player, 'A', true));
    });
    benchPlayers.A.forEach(player => {
        benchPlayersA.appendChild(createSubPlayerCard(player, 'A', false));
    });

    // Render Team B
    activePlayers.B.forEach(player => {
        activePlayersB.appendChild(createSubPlayerCard(player, 'B', true));
    });
    benchPlayers.B.forEach(player => {
        benchPlayersB.appendChild(createSubPlayerCard(player, 'B', false));
    });
}

// Create substitution player card
function createSubPlayerCard(player, team, isActive) {
    const card = document.createElement('div');
    card.className = `sub-player-card ${isActive ? 'active-player' : 'bench-player'}`;
    card.draggable = true;
    card.dataset.team = team;
    card.dataset.number = player.number || '00';
    card.dataset.playerId = player.id;
    card.dataset.isActive = isActive;

    card.innerHTML = `
        <div class="sub-player-number">${player.number || '00'}</div>
        <div class="sub-player-position">${player.position || 'P'}</div>
        <div class="sub-player-status ${isActive ? 'active' : 'bench'}">${isActive ? 'On Court' : 'Bench'}</div>
    `;

    // Drag events
    card.addEventListener('dragstart', handleDragStart);
    card.addEventListener('dragend', handleDragEnd);
    card.addEventListener('dragover', handleDragOver);
    card.addEventListener('drop', handleDrop);
    card.addEventListener('dragenter', handleDragEnter);
    card.addEventListener('dragleave', handleDragLeave);

    return card;
}

// Drag and Drop Handlers
let draggedElement = null;

function handleDragStart(e) {
    draggedElement = e.target;
    e.target.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
}

function handleDragEnd(e) {
    e.target.classList.remove('dragging');
    document.querySelectorAll('.sub-player-card').forEach(card => {
        card.classList.remove('drag-over');
    });
    draggedElement = null;
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    e.preventDefault();
    const target = e.target.closest('.sub-player-card');
    if (target && canDropOn(draggedElement, target)) {
        target.classList.add('drag-over');
    }
}

function handleDragLeave(e) {
    const target = e.target.closest('.sub-player-card');
    if (target && !target.contains(e.relatedTarget)) {
        target.classList.remove('drag-over');
    }
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();

    const dropTarget = e.target.closest('.sub-player-card');
    if (dropTarget) {
        dropTarget.classList.remove('drag-over');
        
        if (draggedElement && draggedElement !== dropTarget && canDropOn(draggedElement, dropTarget)) {
            makeSubstitution(draggedElement, dropTarget);
        }
    }
    return false;
}

// Check if can drop
function canDropOn(source, target) {
    if (!source || !target || source === target) return false;

    const sourceCard = source.closest('.sub-player-card');
    const targetCard = target.closest('.sub-player-card');
    
    if (!sourceCard || !targetCard) return false;

    const sourceTeam = sourceCard.dataset.team;
    const targetTeam = targetCard.dataset.team;
    const sourceActive = sourceCard.dataset.isActive === 'true';
    const targetActive = targetCard.dataset.isActive === 'true';

    // Same team, bench to active only
    return sourceTeam === targetTeam && !sourceActive && targetActive;
}

// Make substitution
function makeSubstitution(benchCard, activeCard) {
    const team = benchCard.dataset.team;
    const benchPlayerId = benchCard.dataset.playerId;
    const activePlayerId = activeCard.dataset.playerId;

    if (team === 'A' && substitutionsA >= maxSubstitutionsA) {
        alert('Team A has no more substitutions remaining this set');
        return;
    } else if (team === 'B' && substitutionsB >= maxSubstitutionsB) {
        alert('Team B has no more substitutions remaining this set');
        return;
    }

    // Find players
    const benchPlayerIndex = benchPlayers[team].findIndex(p => p.id.toString() === benchPlayerId);
    const activePlayerIndex = activePlayers[team].findIndex(p => p.id.toString() === activePlayerId);

    if (benchPlayerIndex === -1 || activePlayerIndex === -1) {
        console.error('Players not found');
        return;
    }

    // Swap players
    const benchPlayer = benchPlayers[team][benchPlayerIndex];
    const activePlayer = activePlayers[team][activePlayerIndex];

    benchPlayers[team][benchPlayerIndex] = activePlayer;
    activePlayers[team][activePlayerIndex] = benchPlayer;

    // Update substitution count
    if (team === 'A') {
        substitutionsA++;
        document.getElementById('substitutionsA').textContent = substitutionsA;
    } else {
        substitutionsB++;
        document.getElementById('substitutionsB').textContent = substitutionsB;
    }

    // Log event
    logEvent(team, `${activePlayer.number}→${benchPlayer.number}`, 'Substitution', 0);

    // Update displays
    renderSubstitutionPlayers();
    renderTeamJerseys();
    updateMainRoster();

    // Show success message
    showSubstitutionSuccess(team, activePlayer.number, benchPlayer.number);
}

// Update main roster display
function updateMainRoster() {
    const playersAGrid = document.getElementById('playersA');
    const playersBGrid = document.getElementById('playersB');
    
    playersAGrid.innerHTML = '';
    playersBGrid.innerHTML = '';

    activePlayers.A.forEach(player => {
        playersAGrid.appendChild(createPlayerCard(player, 'A'));
    });

    activePlayers.B.forEach(player => {
        playersBGrid.appendChild(createPlayerCard(player, 'B'));
    });
}

// Show substitution success
function showSubstitutionSuccess(team, outNumber, inNumber) {
    const message = document.createElement('div');
    message.style.cssText = `
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: #4CAF50;
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: bold;
        z-index: 10000;
        animation: fadeIn 0.3s;
    `;
    message.textContent = `Team ${team}: Player #${outNumber} → #${inNumber}`;
    document.body.appendChild(message);

    setTimeout(() => {
        document.body.removeChild(message);
    }, 3000);
}

function getWinningScore(setNumber) {
  return setNumber === 5 ? 15 : 25;
}

function checkSetWin() {
    // ✅ Don't check for set win if game is already over
    if (setsA >= 3 || setsB >= 3) {
        return;
    }

    const maxPoints = currentSet === 5 ? 15 : 25;
    const minLead = 2;

    if (scoreA >= maxPoints && scoreA - scoreB >= minLead) {
        handleSetWin('A');
    } else if (scoreB >= maxPoints && scoreB - scoreA >= minLead) {
        handleSetWin('B');
    }
}


function startNextSet() {
    document.getElementById('setEndModal').classList.remove('show');
    
    // Move to next set
    currentSet++;
    
    // Reset scores for new set
    scoreA = 0;
    scoreB = 0;
    
    // Reset timeouts and substitutions per set
    timeoutsA = 0;
    timeoutsB = 0;
    substitutionsA = 0;
    substitutionsB = 0;

    // Switch serve
    serving = serving === 'A' ? 'B' : 'A';

    updateScoreboard();
    updateServingIndicator();
    updateSetScoresDisplay();
    logEvent('GAME', 'SYSTEM', `Set ${currentSet} Started`, 0);
}




// Event Listeners
if (substitutionBtn) {
    substitutionBtn.addEventListener('click', openSubstitutionModal);
}

if (subCloseBtn) {
    subCloseBtn.addEventListener('click', closeSubstitutionModal);
}

substitutionModal.addEventListener('click', (e) => {
    if (e.target === substitutionModal) {
        closeSubstitutionModal();
    }
});

// Update the init function to call initializePlayerRosters

        // Initialize
        function init() {
            initializePlayerRosters(); // populate activePlayers/benchPlayers
            // render from activePlayers so draggable reordering is based on that state
            updateMainRoster();
            updateScoreboard();
            updateServingIndicator();
            setupEventListeners();
            loadHotkeys();
            loadGameSettings();
        }

        // Hotkeys functionality
let hotkeys = {
    'kill': 'K',
    'ace': 'A',
    'block': 'B',
    'dig': 'D',
    'assist': 'S',
    'error': 'E',
    'timeout': 'T',
    'undo': 'Z'
};

const defaultHotkeys = { ...hotkeys };

// Load saved hotkeys from localStorage
function loadHotkeys() {
    const saved = localStorage.getItem('volleyballHotkeys');
    if (saved) {
        hotkeys = JSON.parse(saved);
    }
    applyHotkeys();
}

// Save hotkeys to localStorage
function saveHotkeys() {
    localStorage.setItem('volleyballHotkeys', JSON.stringify(hotkeys));
}

// Apply hotkeys (global keyboard listener)
function applyHotkeys() {
    document.addEventListener('keydown', handleHotkeyPress);
}

// Handle hotkey press
function handleHotkeyPress(e) {
    // Ignore if typing in input fields or modal is open
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    if (document.getElementById('hotkeysModal').classList.contains('show')) return;
    if (selectingPlayer || selectingTeam) return;

    const key = e.key.toUpperCase();

    // Find action for this key
    for (const [action, hotkey] of Object.entries(hotkeys)) {
        if (hotkey.toUpperCase() === key) {
            e.preventDefault();
            executeHotkeyAction(action);
            break;
        }
    }
}

// Execute action based on hotkey
function executeHotkeyAction(action) {
    if (action === 'timeout') {
        document.getElementById('timeoutBtn').click();
    } else if (action === 'undo') {
        document.getElementById('undoBtn').click();
    } else {
        const btn = document.querySelector(`[data-action="${action}"]`);
        if (btn) btn.click();
    }
}

// Open hotkeys modal
function openHotkeysModal() {
    document.getElementById('hotkeysModal').classList.add('show');
    updateHotkeyDisplay();
}

// Close hotkeys modal
function closeHotkeysModal() {
    document.getElementById('hotkeysModal').classList.remove('show');
    document.querySelectorAll('.hotkey-input').forEach(input => {
        input.classList.remove('listening');
    });
}

// Update hotkey display in modal
function updateHotkeyDisplay() {
    for (const [action, key] of Object.entries(hotkeys)) {
        const element = document.getElementById(`key-${action}`);
        if (element) {
            element.textContent = key.toUpperCase();
        }
    }
}

// Setup hotkey input listeners
function setupHotkeyInputs() {
    const inputs = document.querySelectorAll('.hotkey-input');
    let listeningInput = null;

    inputs.forEach(input => {
        input.addEventListener('click', function() {
            inputs.forEach(i => i.classList.remove('listening'));
            this.classList.add('listening');
            listeningInput = this;
            
            const keyDisplay = this.querySelector('.current-key');
            keyDisplay.textContent = 'Press a key...';
        });
    });

    document.addEventListener('keydown', function(e) {
        if (listeningInput && document.getElementById('hotkeysModal').classList.contains('show')) {
            e.preventDefault();
            
            const action = listeningInput.dataset.action;
            
            if (e.key === 'Escape') {
                hotkeys[action] = '';
                listeningInput.querySelector('.current-key').textContent = 'NONE';
            } else {
                const newKey = e.key.toUpperCase();
                hotkeys[action] = newKey;
                listeningInput.querySelector('.current-key').textContent = newKey;
            }
            
            listeningInput.classList.remove('listening');
            listeningInput = null;
        }
    });
}

// Reset hotkeys to defaults
function resetHotkeysToDefault() {
    if (confirm('Reset all hotkeys to default values?')) {
        hotkeys = { ...defaultHotkeys };
        updateHotkeyDisplay();
        saveHotkeys();
        showNotification('Hotkeys reset to defaults');
    }
}

// Save hotkeys settings
function saveHotkeysSettings() {
    saveHotkeys();
    closeHotkeysModal();
    showNotification('Hotkeys saved successfully');
}

// Load game settings from localStorage
function loadGameSettings() {
    const saved = localStorage.getItem('volleyballSettings');
    if (saved) {
        const settings = JSON.parse(saved);
        maxTimeoutsA = settings.maxTimeoutsA || 2;
        maxTimeoutsB = settings.maxTimeoutsB || 2;
        maxSubstitutionsA = settings.maxSubstitutionsA || 6;
        maxSubstitutionsB = settings.maxSubstitutionsB || 6;
    }
    updateSettingsDisplay();
}

// Save game settings to localStorage
function saveGameSettings() {
    maxTimeoutsA = parseInt(document.getElementById('maxTimeoutsAInput').value) || 2;
    maxTimeoutsB = parseInt(document.getElementById('maxTimeoutsBInput').value) || 2;
    maxSubstitutionsA = parseInt(document.getElementById('maxSubstitutionsAInput').value) || 6;
    maxSubstitutionsB = parseInt(document.getElementById('maxSubstitutionsBInput').value) || 6;

    localStorage.setItem('volleyballSettings', JSON.stringify({
        maxTimeoutsA,
        maxTimeoutsB,
        maxSubstitutionsA,
        maxSubstitutionsB
    }));
    updateSettingsDisplay();
    closeGameSettingsModal();
    showNotification('Settings saved successfully');
}

// Open game settings modal
function openGameSettingsModal() {
    document.getElementById('maxTimeoutsAInput').value = maxTimeoutsA;
    document.getElementById('maxTimeoutsBInput').value = maxTimeoutsB;
    document.getElementById('maxSubstitutionsAInput').value = maxSubstitutionsA;
    document.getElementById('maxSubstitutionsBInput').value = maxSubstitutionsB;
    document.getElementById('gameSettingsModal').classList.add('show');
}

// Close game settings modal
function closeGameSettingsModal() {
    document.getElementById('gameSettingsModal').classList.remove('show');
}

// Reset settings to defaults
function resetSettingsToDefault() {
    if (confirm('Reset all settings to default values?')) {
        maxTimeoutsA = 2;
        maxTimeoutsB = 2;
        maxSubstitutionsA = 6;
        maxSubstitutionsB = 6;
        openGameSettingsModal(); // Update inputs
        saveGameSettings();
    }
}

// Update settings display in scoreboard
function updateSettingsDisplay() {
    document.getElementById('maxTimeoutsA').textContent = maxTimeoutsA;
    document.getElementById('maxTimeoutsB').textContent = maxTimeoutsB;
    document.getElementById('maxSubstitutionsA').textContent = maxSubstitutionsA;
    document.getElementById('maxSubstitutionsB').textContent = maxSubstitutionsB;
}



// Initialize hotkeys and settings when menu is clicked
document.addEventListener('DOMContentLoaded', function() {
    const hotkeysBtn = document.getElementById('hotkeysBtn');
    if (hotkeysBtn) {
        hotkeysBtn.addEventListener('click', function(e) {
            e.preventDefault();
            hamburgerBtn.classList.remove('active');
            menuDropdown.classList.remove('show');
            openHotkeysModal();
        });
    }
    
    const gameSettingsBtn = document.getElementById('gameSettingsBtn');
    if (gameSettingsBtn) {
        gameSettingsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            hamburgerBtn.classList.remove('active');
            menuDropdown.classList.remove('show');
            openGameSettingsModal();
        });
    }
    
    setupHotkeyInputs();
    loadHotkeys();
});

        

        function createPlayerCard(player, team) {
            const card = document.createElement('div');
            card.className = `player-card team-${team.toLowerCase()}`;
            card.dataset.team = team;
            card.dataset.number = player.number || '00';
            card.dataset.playerId = player.id;
            // include a small Set-Server button inside the card (stops propagation)
            card.innerHTML = `
                <button class="set-server-btn" title="Set as server">S</button>
                <div class="player-number">${player.number || '00'}</div>
                <div class="player-position">${player.position || 'P'}</div>
            `;

            // Make the card draggable for roster reordering
            card.draggable = true;

            // Roster drag event handlers (defined below)
            card.addEventListener('dragstart', rosterDragStart);
            card.addEventListener('dragend', rosterDragEnd);
            card.addEventListener('dragenter', rosterDragEnter);
            card.addEventListener('dragleave', rosterDragLeave);
            card.addEventListener('dragover', rosterDragOver);
            card.addEventListener('drop', rosterDropOnCard);

            // Attach click handler for selecting player for actions
            card.addEventListener('click', () => handlePlayerClick(team, player));

            // Wire up the set-server button (inside the card)
            const btn = card.querySelector('.set-server-btn');
            if (btn) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    setServer(team, player.id);
                });
            }
            return card;
        }

        // Roster drag helpers
        let rosterDragging = null; // { playerId, fromTeam }

        function rosterDragStart(e) {
            const el = e.currentTarget;
            rosterDragging = { playerId: el.dataset.playerId, fromTeam: el.dataset.team };
            el.classList.add('dragging');
            try { e.dataTransfer.setData('text/plain', el.dataset.playerId); } catch (err) {}
            e.dataTransfer.effectAllowed = 'move';
        }

        function rosterDragEnd(e) {
            document.querySelectorAll('.player-card').forEach(c => c.classList.remove('dragging', 'drag-over'));
            rosterDragging = null;
        }

        function rosterDragEnter(e) {
            e.preventDefault();
            const el = e.currentTarget;
            el.classList.add('drag-over');
        }

        function rosterDragLeave(e) {
            const el = e.currentTarget;
            el.classList.remove('drag-over');
        }

        function rosterDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            return false;
        }

        // Drop onto an existing card — insert before the drop target
        function rosterDropOnCard(e) {
            e.preventDefault();
            const dropCard = e.currentTarget;
            dropCard.classList.remove('drag-over');
            if (!rosterDragging) return;

            const destGrid = dropCard.closest('.players-grid');
            if (!destGrid) return;
            const destTeam = destGrid.id === 'playersA' ? 'A' : 'B';

            const srcTeam = rosterDragging.fromTeam;
            const playerId = rosterDragging.playerId;

            // Disallow cross-team moves
            if (srcTeam !== destTeam) {
                showNotification('Cannot move player to the other team');
                rosterDragEnd();
                return;
            }

            const arr = srcTeam === 'A' ? activePlayers.A : activePlayers.B;
            const srcIdx = arr.findIndex(p => p.id.toString() === playerId.toString());
            if (srcIdx === -1) return;

            // find index of dropCard among cards in dest grid (same array)
            const cards = Array.from(destGrid.querySelectorAll('.player-card'));
            const destIdx = cards.indexOf(dropCard);
            if (destIdx === -1) return;

            // Swap source and destination items
            if (srcIdx !== destIdx) {
                const tmp = arr[destIdx];
                arr[destIdx] = arr[srcIdx];
                arr[srcIdx] = tmp;
            }

            // Re-render UI
            renderTeamJerseys();
            updateMainRoster();
            renderSubstitutionPlayers();

            rosterDragEnd();
        }

        // Allow dropping into empty grid area (append)
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.players-grid').forEach(grid => {
                grid.addEventListener('dragover', (e) => { e.preventDefault(); });
                grid.addEventListener('drop', (e) => {
                    e.preventDefault();
                    if (!rosterDragging) return;
                    const destTeam = grid.id === 'playersA' ? 'A' : 'B';
                    const srcTeam = rosterDragging.fromTeam;
                    const playerId = rosterDragging.playerId;

                    // prevent cross-team drops
                    if (srcTeam !== destTeam) {
                        showNotification('Cannot move player to the other team');
                        rosterDragEnd();
                        return;
                    }

                    const arr = srcTeam === 'A' ? activePlayers.A : activePlayers.B;
                    const idx = arr.findIndex(p => p.id.toString() === playerId.toString());
                    if (idx === -1) return;

                    // Move to end inside same team
                    const [playerObj] = arr.splice(idx, 1);
                    arr.push(playerObj);

                    renderTeamJerseys();
                    updateMainRoster();
                    renderSubstitutionPlayers();
                    rosterDragEnd();
                });
            });
        });

        function setupEventListeners() {
            document.querySelectorAll('.action-btn[data-action]').forEach(btn => {
                btn.addEventListener('click', () => handleActionClick(btn.dataset.action));
            });

            document.getElementById('timeoutBtn').addEventListener('click', handleTimeoutClick);
            document.getElementById('undoBtn').addEventListener('click', handleUndo);
            document.getElementById('selectTeamA').addEventListener('click', () => handleTeamSelect('A'));
            document.getElementById('selectTeamB').addEventListener('click', () => handleTeamSelect('B'));
        }

        function handleActionClick(action) {
            document.querySelectorAll('.action-btn').forEach(btn => btn.classList.remove('selected'));
           
            selectedAction = action;
           
            event.target.classList.add('selected');

            if (action === 'error') {
                selectingTeam = true;
                teamSelectCallback = handleErrorTeamSelect;
                showTeamSelectModal('Which team committed the error?');
            } else {
                selectingPlayer = true;
                document.querySelectorAll('.player-card').forEach(card => card.classList.add('selecting'));
                showInstruction(`Select player for ${action.toUpperCase()}`);
            }
        }

        // ✅ NEW: Global click handler to cancel action selection
document.addEventListener('click', function(e) {
    // Only handle if we're currently selecting
    if (!selectingPlayer && !selectingTeam) return;
    
    // Check what was clicked
    const clickedPlayerCard = e.target.closest('.player-card');
    const clickedActionBtn = e.target.closest('.action-btn');
    const clickedModal = e.target.closest('.modal-content');
    const clickedBanner = e.target.closest('.instruction-banner');
    
    // If clicked outside all valid targets, cancel selection
    if (!clickedPlayerCard && !clickedActionBtn && !clickedModal && !clickedBanner) {
        console.log('Clicked outside - canceling action selection');
        resetSelection();
    }
});

        function handlePlayerClick(team, player) {
            if (!selectingPlayer) return;

            const playerNumber = player.number || '00';
           
            if (selectedAction === 'kill' || selectedAction === 'ace' || selectedAction === 'block') {
                handleScore(team, selectedAction, playerNumber);
            } else if (selectedAction === 'dig' || selectedAction === 'assist') {
                logEvent(team, playerNumber, selectedAction.charAt(0).toUpperCase() + selectedAction.slice(1), 0);
            }

            resetSelection();
        }

        function handleScore(team, action, playerNumber) {
    // ✅ Prevent scoring if game is already over
    if (setsA >= 3 || setsB >= 3) {
        return;
    }

    const actionLabel = action.charAt(0).toUpperCase() + action.slice(1);
    
    if (team === 'A') {
        scoreA++;
        updateScoreDisplay();
        logEvent('A', playerNumber, actionLabel, 1);
    } else {
        scoreB++;
        updateScoreDisplay();
        logEvent('B', playerNumber, actionLabel, 1);
    }

    if (team !== serving) {
        // team gained the serve -> rotate that team's players (visual mapping)
        serving = team;
        rotateTeamClockwise(team);
        // update current server to the player in the server position (pos 1 -> index 5)
        const serverIndexMap = {1:5,2:2,3:1,4:0,5:3,6:4};
        const arr = team === 'A' ? activePlayers.A : activePlayers.B;
        const newServer = arr && arr[serverIndexMap[1]] ? arr[serverIndexMap[1]] : null;
        if (newServer) {
            currentServerId = newServer.id;
            currentServerTeam = team;
        }
        updateServingIndicator();
        highlightServerBadge();
        logEvent('GAME', 'SYSTEM', `Serve → Team ${team}`, 0);
    }

    checkSetWin();
}

        function checkSetWin() {
            const maxPoints = currentSet < 5 ? 25 : 15;
            const minLead = 2;

            if (scoreA >= maxPoints && scoreA - scoreB >= minLead) {
                handleSetWin('A');
            } else if (scoreB >= maxPoints && scoreB - scoreA >= minLead) {
                handleSetWin('B');
            }
        }

        function handleSetWin(winner) {
    // Save current set scores BEFORE incrementing
    setScores.A[currentSet - 1] = scoreA;
    setScores.B[currentSet - 1] = scoreB;

    // Increment sets won for the winner
    if (winner === 'A') {
        setsA++;
    } else {
        setsB++;
    }

    // Log the set end
    logEvent('GAME', 'SYSTEM', `Set ${currentSet} Ended - Team ${winner} wins ${winner === 'A' ? scoreA : scoreB}-${winner === 'A' ? scoreB : scoreA}`, 0);

    // Update displays
    updateScoreboard();
    updateSetScoresDisplay();

    // ✅ CRITICAL: Check if game is over IMMEDIATELY (first to 3 sets wins)
    if (setsA >= 3 || setsB >= 3) {
        // Game is over - show game end modal
        setTimeout(() => showGameEndModal(), 1000);
    } else {
        // Game continues - show set end modal for next set
        setTimeout(() => showSetEndModal(), 1000);
    }
}

        function handleTimeoutClick() {
            selectingTeam = true;
            teamSelectCallback = handleTimeoutTeamSelect;
            showTeamSelectModal('Which team is taking a timeout?');
        }

        function handleTimeoutTeamSelect(team) {
            if (team === 'A' && timeoutsA >= maxTimeoutsA) {
                alert('Team A has no timeouts remaining');
                return;
            }
            if (team === 'B' && timeoutsB >= maxTimeoutsB) {
                alert('Team B has no timeouts remaining');
                return;
            }

            if (team === 'A') {
                timeoutsA++;
            } else {
                timeoutsB++;
            }

            updateScoreboard();
            logEvent(team, 'TEAM', 'Timeout', 0);
            startTimeoutTimer();
        }

        function handleErrorTeamSelect(team) {
            const opponent = team === 'A' ? 'B' : 'A';
           
            if (opponent === 'A') {
                scoreA++;
            } else {
                scoreB++;
            }

            updateScoreDisplay();
            logEvent(team, 'TEAM', 'Error (Opponent scores)', 1);

            if (opponent !== serving) {
                serving = opponent;
                updateServingIndicator();
                logEvent('GAME', 'SYSTEM', `Serve → Team ${opponent}`, 0);
            }

            checkSetWin();
        }

        function startTimeoutTimer() {
            timeoutActive = true;
            timeoutTime = 30;
            document.getElementById('timeoutTimer').classList.add('active');
            updateTimeoutDisplay();

            timeoutInterval = setInterval(() => {
                timeoutTime--;
                updateTimeoutDisplay();
                if (timeoutTime <= 0) {
                    endTimeout();
                }
            }, 1000);
        }

        function endTimeout() {
            timeoutActive = false;
            clearInterval(timeoutInterval);
            document.getElementById('timeoutTimer').classList.remove('active');
            timeoutTime = 30;
        }

        function updateTimeoutDisplay() {
            document.getElementById('timeoutTime').textContent = timeoutTime;
        }

        function handleTeamSelect(team) {
            closeTeamSelectModal();
            if (teamSelectCallback) {
                teamSelectCallback(team);
                teamSelectCallback = null;
            }
            selectingTeam = false;
        }

        function showTeamSelectModal(prompt) {
            document.getElementById('teamSelectPrompt').textContent = prompt;
            document.getElementById('teamSelectModal').classList.add('show');
        }

        function closeTeamSelectModal() {
            document.getElementById('teamSelectModal').classList.remove('show');
            selectingTeam = false;
            resetSelection();
        }

        function showSetEndModal() {
            document.getElementById('endedSetNumber').textContent = currentSet;
            document.getElementById('setEndTeamA').textContent = gameData.team1.name;
            document.getElementById('setEndTeamB').textContent = gameData.team2.name;
            document.getElementById('setEndScoreA').textContent = scoreA.toString().padStart(2, '0');
            document.getElementById('setEndScoreB').textContent = scoreB.toString().padStart(2, '0');
            document.getElementById('modalSetsA').textContent = setsA;
            document.getElementById('modalSetsB').textContent = setsB;
            document.getElementById('nextSetNumber').textContent = currentSet + 1;
            document.getElementById('setEndModal').classList.add('show');
        }

        function startNextSet() {
            document.getElementById('setEndModal').classList.remove('show');
           
            currentSet++;
            scoreA = 0;
            scoreB = 0;
            timeoutsA = 0;
            timeoutsB = 0;
            substitutionsA = 0;
            substitutionsB = 0;

            serving = serving === 'A' ? 'B' : 'A';

            updateScoreboard();
            updateServingIndicator();
            updateSetScoresDisplay();
            logEvent('GAME', 'SYSTEM', `Set ${currentSet} Started`, 0);
        }

        function showGameEndModal() {
    const winner = setsA > setsB ? gameData.team1.name : gameData.team2.name;
    document.getElementById('winnerText').textContent = `${winner.toUpperCase()} WINS!`;
    document.getElementById('finalSetsA').textContent = setsA;
    document.getElementById('finalSetsB').textContent = setsB;

    let setScoresHtml = '';
    
    // ✅ ONLY show sets that were actually played (not empty sets)
    for (let i = 0; i < currentSet; i++) {
        const scoreA = setScores.A[i];
        const scoreB = setScores.B[i];
        
        // Only show if this set has scores
        if (scoreA !== undefined && scoreB !== undefined && (scoreA > 0 || scoreB > 0)) {
            const wonClass = scoreA > scoreB ? 'won-a' : 'won-b';

            setScoresHtml += `
                <div style="display: flex; justify-content: space-between; padding: 10px; background: #3d3d3d; margin-bottom: 8px; border-radius: 6px;" class="${wonClass}">
                    <span>Set ${i + 1}:</span>
                    <span style="font-family: 'Courier New', monospace; font-weight: bold;">${scoreA} - ${scoreB}</span>
                </div>
            `;
        }
    }

    document.getElementById('finalSetScores').innerHTML = setScoresHtml;
    document.getElementById('gameEndModal').classList.add('show');
}

        function collectPlayerStats() {
            const playerStats = {};

            function findPlayerByNumber(playerNumber, team) {
                const players = team === 'A' ? gameData.team1.players : gameData.team2.players;
                return players.find(p => (p.number || '00').toString() === playerNumber.toString());
            }

            [...gameData.team1.players].forEach(player => {
                const key = `A_${player.id}`;
                playerStats[key] = {
                    player_id: player.id,
                    team_id: player.team_id,
                    kills: 0,
                    aces: 0,
                    blocks: 0,
                    digs: 0,
                    assists: 0,
                    errors: 0,
                    service_errors: 0,
                    attack_attempts: 0,
                    block_assists: 0
                };
            });

            [...gameData.team2.players].forEach(player => {
                const key = `B_${player.id}`;
                playerStats[key] = {
                    player_id: player.id,
                    team_id: player.team_id,
                    kills: 0,
                    aces: 0,
                    blocks: 0,
                    digs: 0,
                    assists: 0,
                    errors: 0,
                    service_errors: 0,
                    attack_attempts: 0,
                    block_assists: 0
                };
            });

            events.forEach(event => {
                if (event.player === 'TEAM' || event.player === 'SYSTEM') {
                    return;
                }

                const player = findPlayerByNumber(event.player, event.team);
                if (!player) return;

                const key = `${event.team}_${player.id}`;
                if (!playerStats[key]) return;

                if (event.action === 'Kill') {
                    playerStats[key].kills++;
                    playerStats[key].attack_attempts++;
                } else if (event.action === 'Ace') {
                    playerStats[key].aces++;
                } else if (event.action === 'Block') {
                    playerStats[key].blocks++;
                } else if (event.action === 'Dig') {
                    playerStats[key].digs++;
                } else if (event.action === 'Assist') {
                    playerStats[key].assists++;
                } else if (event.action.includes('Error')) {
                    playerStats[key].errors++;
                    if (event.action.includes('Service')) {
                        playerStats[key].service_errors++;
                    }
                }
            });

            return Object.values(playerStats);
        }

        function saveGameResults() {
            const playerStats = collectPlayerStats();
    
            const finalGameData = {
                game_id: gameData.id,
                team1_score: setsA,
                team2_score: setsB,
                set_scores: Object.keys(setScores.A).slice(0, currentSet).map((key, index) => ({
                    set: index + 1,
                    team1: setScores.A[index],
                    team2: setScores.B[index]
                })),
                winner_id: setsA > setsB ? 1 : 2,
                status: 'completed',
                completed_at: new Date().toISOString(),
                game_events: events,  // ✅ Events already include 'set' property
                player_stats: playerStats
            };

            fetch(`/games/${gameData.id}/volleyball-complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(finalGameData)
            })
            .then(response => response.json())
            .then(data => {
                alert('Game saved successfully!');
                window.location.href = data.redirect_url || '/games';
            })
            .catch(error => {
                console.error('Error saving game:', error);
                alert('Error saving game. Please try again.');
            });
        }

        function logEvent(team, player, action, points) {
            const event = {
                id: eventCounter++,
                team,
                player,
                action,
                points,
                set: currentSet,  // ✅ This is already there
                score: `${scoreA}-${scoreB}`
            };
            events.unshift(event);
            renderLog();
        }

        function renderLog() {
            const logContent = document.getElementById('logContent');
            logContent.innerHTML = '';

            events.forEach(event => {
                const entry = document.createElement('div');
                entry.className = `log-entry team-${event.team.toLowerCase()}`;
                entry.innerHTML = `
                    <div class="entry-number">#${event.id}</div>
                    <div class="entry-team">${event.team}</div>
                    <div class="entry-player">${event.player}</div>
                    <div class="entry-action">${event.action}</div>
                    <div class="entry-set">S${event.set}</div>
                    <div class="entry-score">${event.score}</div>
                    <div class="entry-check">✓</div>
                `;
                logContent.appendChild(entry);
            });
        }

        function handleUndo() {
            if (events.length === 0) return;

            const lastEvent = events.shift();

            if (lastEvent.points > 0) {
                if (lastEvent.team === 'A') {
                    scoreA = Math.max(0, scoreA - 1);
                } else if (lastEvent.team === 'B') {
                    scoreB = Math.max(0, scoreB - 1);
                } else if (lastEvent.action.includes('Error')) {
                    const errorTeam = lastEvent.team;
                    const opponent = errorTeam === 'A' ? 'B' : 'A';
                    if (opponent === 'A') {
                        scoreA = Math.max(0, scoreA - 1);
                    } else {
                        scoreB = Math.max(0, scoreB - 1);
                    }
                }
                updateScoreDisplay();
            }

            if (lastEvent.action === 'Timeout') {
                if (lastEvent.team === 'A') {
                    timeoutsA = Math.max(0, timeoutsA - 1);
                } else {
                    timeoutsB = Math.max(0, timeoutsB - 1);
                }
            }

            if (lastEvent.action === 'Substitution') {
                if (lastEvent.team === 'A') {
                    substitutionsA = Math.max(0, substitutionsA - 1);
                } else {
                    substitutionsB = Math.max(0, substitutionsB - 1);
                }
            }

            updateScoreboard();
            renderLog();
        }

        function updateScoreboard() {
            document.getElementById('scoreA').textContent = scoreA.toString().padStart(2, '0');
            document.getElementById('scoreB').textContent = scoreB.toString().padStart(2, '0');
            document.getElementById('currentSet').textContent = currentSet;
            document.getElementById('setsA').textContent = setsA;
            document.getElementById('setsB').textContent = setsB;
            document.getElementById('timeoutsA').textContent = timeoutsA;
            document.getElementById('timeoutsB').textContent = timeoutsB;
            document.getElementById('substitutionsA').textContent = substitutionsA;
            document.getElementById('substitutionsB').textContent = substitutionsB;
            updateSettingsDisplay();
        }

        function updateScoreDisplay() {
            document.getElementById('scoreA').textContent = scoreA.toString().padStart(2, '0');
            document.getElementById('scoreB').textContent = scoreB.toString().padStart(2, '0');
        }

        function updateServingIndicator() {
            const servingA = document.getElementById('servingA');
            const servingB = document.getElementById('servingB');

            if (serving === 'A') {
                servingA.style.display = 'block';
                servingB.style.display = 'none';
            } else {
                servingA.style.display = 'none';
                servingB.style.display = 'block';
            }
        }

        function updateSetScoresDisplay() {
            const display = document.getElementById('setScoresDisplay');
            display.innerHTML = '';

            for (let i = 0; i < currentSet - 1; i++) {
                const scoreA = setScores.A[i];
                const scoreB = setScores.B[i];
                const wonClass = scoreA > scoreB ? 'won-a' : 'won-b';
               
                const box = document.createElement('div');
                box.className = `set-score-box ${wonClass}`;
                box.textContent = `${scoreA}-${scoreB}`;
                display.appendChild(box);
            }
        }

        function showInstruction(message) {
            const banner = document.getElementById('instructionBanner');
            banner.textContent = message;
            banner.classList.add('show');
        }

        function hideInstruction() {
            const banner = document.getElementById('instructionBanner');
            banner.classList.remove('show');
        }

        function resetSelection() {
            selectedAction = null;
            selectingPlayer = false;
             blockingTeam = null;        // ✅ ADD this line
            pendingBlockType = null;    // ✅ ADD this line
            teamSelectCallback = null;  // ✅ ADD this line
            document.querySelectorAll('.action-btn').forEach(btn => btn.classList.remove('selected'));
            document.querySelectorAll('.player-card').forEach(card => card.classList.remove('selecting'));
            hideInstruction();
        }

        init();
    </script>
</body>
</html>