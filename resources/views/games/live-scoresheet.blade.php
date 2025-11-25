<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Game - {{ $game->team1->team_name }} vs {{ $game->team2->team_name }}</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #1a1a1a;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            max-height: 100vh;
        }

        .foul-dot.tech {
            background: #9C27B0;
            border-color: #7B1FA2;
            box-shadow: 0 0 4px rgba(156, 39, 176, 0.6);
        }



        /* Top Scoreboard Bar */
        .scoreboard {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 8px 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            position: relative;
            min-height: 70px;
            flex-shrink: 0;

        }

        /* Hamburger/Menu (volleyball-style) */
        .menu-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 10000;
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
            display: block;
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
            background: none;
        }

        .menu-item:last-child { border-bottom: none; }

        .menu-item:hover {
            background: #3d3d3d;
            padding-left: 25px;
        }

        .menu-item-icon { font-size: 22px; width: 28px; text-align: center; }

        .menu-item-text { font-size: 15px; font-weight: 600; }

        .team-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        /* Dropdown replaced by volleyball-style menu-dropdown above */


        /* Improved team section alignment */
        .team-section {
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.2s;
            min-width: 200px;
        }

        .team-section.left {
            justify-content: flex-start;
        }

        .team-section.right {
            justify-content: flex-end;
            flex-direction: row-reverse;
        }


        .team-section.timeout-selectable {
            outline: 2px solid #4CAF50;
            outline-offset: 2px;
            background: rgba(76, 175, 80, 0.1);
        }

        .team-section.timeout-selectable:hover {
            background: rgba(76, 175, 80, 0.2);
        }

        .team-name {
            font-size: 14px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Better team stats alignment */
        .team-stats {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: #ccc;
        }

        .stat-item {
            font-weight: 600;
        }

        .team-section.right .team-stats {
            text-align: right;
        }

        .score-display {
            font-size: 36px;
            font-weight: bold;
            color: #4CAF50;
            min-width: 60px;
            text-align: center;
            line-height: 1;
            font-family: 'Courier New', monospace;
        }

        .center-panel {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
            justify-content: center;
            padding: 0 20px;
        }

        .timer-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .timer {
            font-size: 32px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            color: #4CAF50;
            min-width: 100px;
            text-align: center;
        }

        .period-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .period {
            font-size: 14px;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 6px 10px;
            border-radius: 4px;
            font-weight: 600;
        }

        .play-btn {
            font-size: 20px;
            cursor: pointer;
            background: #4CAF50;
            border: none;
            color: white;
            padding: 10px 14px;
            border-radius: 6px;
            transition: all 0.2s;
            min-width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Play button when positioned at the far right */
        .play-btn.play-btn-right {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1002;
        }

        /* Shot clock displayed under the period/quarter */
        .shot-clock {
            margin-top: 6px;
            background: #888;
            color: white;
            font-weight: 800;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 15px;
            min-width: 44px;
            text-align: center;
        }

        .play-btn:hover {
            background: #45a049;
        }

        .possession-arrow {
            display: flex;
            align-items: center;
        }

        .arrow {
            font-size: 24px;
            color: #4CAF50;
            font-weight: bold;
        }

        /* Ball Possession Arrows */
        .possession-indicators {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .possession-arrow {
            font-size: 20px;
            color: #666;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 6px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid transparent;
        }

        .possession-arrow:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #999;
        }

        .possession-arrow.active {
            color: #FF9800;
            background: rgba(255, 152, 0, 0.2);
            border-color: #FF9800;
            animation: possessionPulse 2s infinite;
        }

        @keyframes possessionPulse {

            0%,
            100% {
                box-shadow: 0 0 8px rgba(255, 152, 0, 0.4);
            }

            50% {
                box-shadow: 0 0 15px rgba(255, 152, 0, 0.6);
            }
        }

        .possession-label {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .max-timeouts {
            color: #888;
        }

        /* Main Layout */
        .container {
            display: grid;
            grid-template-columns: minmax(300px, 320px) 1fr minmax(300px, 320px);
            grid-template-rows: minmax(0, 1fr);
            gap: 1px;
            flex: 1;
            overflow: hidden;
            width: 100vw;
            max-width: 100%;
            min-height: 0;
            max-height: 100%;
        }

        /* Player Roster */
        .roster-section {
            background: #2d2d2d;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #444;
            height: 100%;
            overflow: hidden;
            min-height: 0;
            max-height: 100%;
        }

        .roster-header {
            display: flex;
            background: #333;
            border-bottom: 1px solid #444;
            flex-shrink: 0;
        }

        .team-tab {
            flex: 1;
            padding: 16px 12px;
            /* Better vertical padding */
            text-align: center;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 50px;
            /* Consistent height */
        }

        .team-tab.active {
            background: #444;
            border-bottom-color: #4CAF50;
        }

        .team-tab.team-a {
            background: #c33;
        }

        .team-tab.team-b {
            background: #339;
        }

        .team-tab.team-a.active {
            background: #d44;
        }

        .team-tab.team-b.active {
            background: #44a;
        }

        .players-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 20px;
            flex: 1;
            overflow-y: auto;
            min-height: 0;
            max-height: 100%;
        }

        .player-card {
            background: #3d3d3d;
            border-radius: 10px;
            /* Increased from 8px */
            padding: 16px 12px;
            /* Better padding */
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
            text-align: center;
            min-height: 70px;
            /* Consistent height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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

        .player-number {
            font-size: 20px;
            /* Increased from 18px */
            font-weight: bold;
            line-height: 1;
        }

        .player-position {
            font-size: 12px;
            /* Increased from 11px */
            color: #aaa;
            margin-top: 4px;
            /* Increased from 2px */
        }

        .player-lastname {
            font-size: 12px;
            color: #ddd;
            margin-top: 6px;
            font-weight: 600;
            text-transform: capitalize;
        }

        /* Event Log */
        .log-section {
            background: #1e1e1e;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #444;
            height: 100%;
            overflow: hidden;
            min-height: 0;
            max-height: calc(100vh - 70px);
            /* 70px is your scoreboard height */
        }


        .log-header {
            padding: 15px 20px;
            background: #333;
            border-bottom: 1px solid #444;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .log-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0;
            min-height: 0;
            max-height: 100%;
            display: flex;
            flex-direction: column;
            scrollbar-width: none;
            /* Firefox - hide scrollbar */
        }

        .log-content:hover {
            scrollbar-width: thin;
            scrollbar-color: #4CAF50 #2a2a2a;
            /* Thumb color and track */
        }

        .log-content::-webkit-scrollbar {
            width: 8px;
            background: transparent;
        }

        .log-content:hover::-webkit-scrollbar {
            width: 8px;
        }

        .log-content::-webkit-scrollbar-thumb {
            background: #4CAF50;
            border-radius: 6px;
        }

        .log-content::-webkit-scrollbar-thumb:hover {
            background: #66bb6a;
        }

        .log-content::-webkit-scrollbar-track {
            background: #2a2a2a;
            border-radius: 6px;
        }


        .log-entry {
            display: grid;
            grid-template-columns: 50px 40px 80px 1fr 70px 70px 40px;
            /* Improved proportions */
            align-items: center;
            padding: 14px 15px;
            /* Increased padding */
            border-bottom: 1px solid #333;
            font-size: 12px;
            transition: background 0.2s;
            gap: 12px;
            /* Increased gap */
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

        .entry-period {
            color: #888;
            font-size: 11px;
        }

        .entry-time {
            color: #888;
            font-size: 11px;
            font-family: 'Courier New', monospace;
        }

        .entry-check {
            color: #4CAF50;
            font-size: 16px;
        }

        /* Actions Panel */
        .actions-section {
            background: #2d2d2d;
            display: flex;
            flex-direction: column;
            padding: 24px 20px;
            gap: 12px;
            height: 100%;
            min-height: 0;
            overflow-y: auto;
            max-height: 100%;


        }

        .actions-header {
            font-size: 16px;
            /* Increased from 14px */
            font-weight: 600;
            margin-bottom: 10px;
            /* Increased from 10px */
            color: #ccc;
            text-align: center;
            padding-bottom: 8px;
            border-bottom: 1px solid #444;
            /* Add separator */
            flex-shrink: 0;
        }

        /* Consistent button sizing */
        .action-btn {
            padding: 16px 20px;
            /* Increased padding */
            font-size: 14px;
            /* Increased from 13px */
            font-weight: 600;
            border: none;
            border-radius: 8px;
            /* Increased from 6px */
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-height: 52px;
            /* Consistent height */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Add grouping separator */
        .action-btn.timeout {
            margin-top: 16px;
            position: relative;
        }

        .action-btn.timeout::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 10%;
            right: 10%;
            height: 1px;
            background: #444;
        }

        .action-btn.substitution {
            margin-bottom: 16px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .action-btn.selected {
            outline: 2px solid #4CAF50;
            outline-offset: 2px;
        }

        .free-throw {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .two-points {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
        }

        .blocks {
            background: linear-gradient(135deg, #9E9E9E 0%, #616161 100%);
            color: white;
        }

        .three-points {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
        }

        .assist {
            background: linear-gradient(135deg, #3F51B5 0%, #303F9F 100%);
            color: white;
        }

        .steal {
            background: linear-gradient(135deg, #009688 0%, #00796B 100%);
            color: white;
        }

        .rebound {
            background: linear-gradient(135deg, #FF5722 0%, #E64A19 100%);
            color: white;
        }

        .foul {
            background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
            color: white;
        }


        .tech {
            background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
            color: white;
        }

        .timeout {
            background: linear-gradient(135deg, #607D8B 0%, #455A64 100%);
            color: white;
        }

        .timeout.timer-active {
            background: linear-gradient(135deg, #FF5722 0%, #D84315 100%);
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        .substitution {
            background: linear-gradient(135deg, #795548 0%, #5D4037 100%);
            color: white;
        }

        .undo-btn {
            background: linear-gradient(135deg, #666 0%, #555 100%);
            color: white;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Free throw attempt overlay */
        .free-throw-panel {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #222;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .ft-attempts {
            display: flex;
            gap: 10px;
        }

        .ft-attempts button {
            padding: 10px 14px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            background: #444;
            color: white;
        }

        .ft-attempts button.made {
            background: green;
        }

        .ft-attempts button.miss {
            background: red;
        }

        .ft-accept {
            padding: 10px 16px;
            background: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            color: white;
        }

        .timeout-instruction {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 1000;
            display: none;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        /* Substitution Modal */
        .substitution-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .substitution-content {
            background: #2d2d2d;
            border-radius: 12px;
            padding: 30px;
            width: 90%;
            max-width: 1000px;
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
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        .sub-close {
            background: #666;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .sub-close:hover {
            background: #777;
        }

        .sub-teams {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .sub-team-section {
            background: #3d3d3d;
            padding: 20px;
            border-radius: 8px;
        }

        .sub-team-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            padding: 10px;
            border-radius: 6px;
        }

        .sub-team-a .sub-team-title {
            background: #c33;
            color: white;
        }

        .sub-team-b .sub-team-title {
            background: #339;
            color: white;
        }

        .sub-section-title {
            font-size: 14px;
            font-weight: 600;
            color: #ccc;
            margin: 15px 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sub-players-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            /* Increased min width */
            gap: 14px;
            /* Increased from 10px */
            margin-bottom: 20px;
        }

        .sub-player-card {
            background: #4d4d4d;
            padding: 16px 12px;
            /* Better padding */
            border-radius: 10px;
            /* Increased from 8px */
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            /* Increased duration */
            border: 2px solid transparent;
            user-select: none;
            min-height: 80px;
            /* Consistent height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .sub-player-card:hover {
            background: #5d5d5d;
            transform: translateY(-2px);
        }

        .sub-player-card.active-player {
            border-color: #4CAF50;
        }

        .sub-player-card.bench-player {
            border-color: #FF9800;
        }

        .sub-player-card.dragging {
            opacity: 0.6;
            /* Slightly more visible */
            transform: scale(0.95) rotate(5deg);
            /* Add rotation */
        }

        .sub-player-card.drag-over {
            background: #6d6d6d;
            border-color: #4CAF50;
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
            /* Add glow */
        }

        .sub-player-number {
            font-size: 18px;
            font-weight: bold;
            color: white;
        }

        .sub-player-position {
            font-size: 11px;
            color: #aaa;
            margin-top: 4px;
        }

        .sub-player-lastname {
            font-size: 12px;
            color: #fff;
            margin-top: 6px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .sub-player-status {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 3px;
            margin-top: 4px;
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

        .sub-instructions {
            text-align: center;
            color: #aaa;
            margin-bottom: 20px;
            padding: 10px;
            background: #333;
            border-radius: 6px;
            font-size: 14px;
        }

        /* Foul Modal Styles */
        .foul-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 3000;
        }

        .foul-content {
            background: #2d2d2d;
            border-radius: 12px;
            padding: 30px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .foul-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #444;
            padding-bottom: 15px;
        }

        .foul-title {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        .foul-close {
            background: #666;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
        }

        .foul-close:hover {
            background: #777;
        }

        .foul-step h3 {
            color: white;
            margin-bottom: 20px;
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        .foul-options {
            display: grid;
            gap: 15px;
            max-width: 600px;
            margin: 0 auto;
        }

        .foul-option-btn {
            padding: 15px 20px;
            background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .foul-option-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(244, 67, 54, 0.4);
        }

        .foul-teams {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        /* Foul buttons row container */
        .foul-buttons-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .foul-buttons-row .action-btn {
            padding: 16px 10px;
            font-size: 13px;
        }

        .foul-team-section {
            background: #3d3d3d;
            padding: 20px;
            border-radius: 8px;
        }

        .foul-team-section h4 {
            color: white;
            text-align: center;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .foul-players-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            /* Better sizing */
            gap: 14px;
            /* Increased gap */
        }

        .foul-player-card {
            background: #4d4d4d;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
            color: white;
            min-height: 70px;
            /* Consistent height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }


        .foul-player-card:hover {
            background: #5d5d5d;
            transform: translateY(-2px);
            border-color: #4CAF50;
        }

        .foul-player-number {
            font-size: 16px;
            font-weight: bold;
        }

        .foul-player-info,
        .foul-info {
            text-align: center;
            color: #4CAF50;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            padding: 10px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 8px;
        }

        /* Single column layout for fouled player selection */
        .fouled-player-teams {
            display: grid;
            grid-template-columns: 1fr;
            max-width: 500px;
            margin: 0 auto;
        }

        .foul-player-position {
            font-size: 11px;
            color: #aaa;
            margin-top: 4px;
        }

        @media (max-width: 1200px) {
            .container {
                grid-template-columns: minmax(280px, 300px) 1fr minmax(280px, 300px);
            }

            .team-section {
                min-width: 180px;
                gap: 15px;
            }

            .score-display {
                font-size: 32px;
                min-width: 60px;
            }
        }

        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 250px 1fr 250px;
            }

            .center-panel {
                gap: 15px;
            }

            .timer {
                font-size: 28px;
            }

            .actions-section {
                padding: 16px;
            }
        }

        * Responsive Design */ @media (max-width: 1024px) {
            .scoreboard {
                padding: 6px 12px;
            }

            .team-section {
                min-width: 160px;
                gap: 12px;
            }

            .score-display {
                font-size: 30px;
                min-width: 50px;
            }

            .timer {
                font-size: 28px;
            }

            .center-panel {
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .team-stats {
                gap: 8px;
            }

            .stat-item {
                font-size: 11px;
            }

            .team-name {
                font-size: 12px;
            }
        }

        /* Foul indicator styles */
        .foul-indicator {
            display: flex;
            gap: 2px;
            margin-top: 4px;
            justify-content: center;
        }

        .foul-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #444;
            border: 1px solid #666;
            transition: all 0.3s;
        }

        .foul-dot.active {
            background: #F44336;
            border-color: #D32F2F;
            box-shadow: 0 0 4px rgba(244, 67, 54, 0.6);
        }

        .foul-dot.warning {
            background: #FF9800;
            border-color: #F57C00;
            animation: pulse-warning 2s infinite;
        }

        @keyframes pulse-warning {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }

        .player-card.fouled-out {
            background: #2a1a1a !important;
            border-color: #F44336 !important;
            opacity: 0.6;
        }

        .player-card.fouled-out .player-number {
            color: #F44336;
        }

        .player-card.warning-fouls {
            border-color: #ff4800 !important;
            box-shadow: 0 0 8px rgba(255, 152, 0, 0.3);
        }

        /* Foul out notification */
        .foul-out-notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #F44336;
            color: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(244, 67, 54, 0.4);
            z-index: 6000;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            animation: foulOutAnimation 0.5s ease-out;
        }

        @keyframes foulOutAnimation {
            0% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0;
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }

        /* Quarter End Modal */
        .quarter-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 7000;
        }

        .quarter-content {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            border: 3px solid #4CAF50;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
            animation: quarterModalAnimation 0.5s ease-out;
        }

        @keyframes quarterModalAnimation {
            0% {
                transform: scale(0.8) rotate(-5deg);
                opacity: 0;
            }

            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        .quarter-title {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .quarter-info {
            font-size: 18px;
            color: #ccc;
            margin-bottom: 30px;
        }

        .quarter-score {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .quarter-team-score {
            text-align: center;
        }

        .quarter-team-name {
            font-size: 16px;
            color: #aaa;
            margin-bottom: 5px;
        }

        .quarter-team-points {
            font-size: 36px;
            font-weight: bold;
            color: white;
        }

        .quarter-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
        }

        .quarter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .game-end-title {
            color: #FF9800;
            font-size: 40px;
        }

        .game-end-btn {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            margin: 10px;
        }

        /* Penalty indicator styles */
        .penalty-active {
            background: rgba(255, 152, 0, 0.2);
            border-radius: 4px;
            padding: 2px 4px;
            border: 1px solid #FF9800;
            animation: penaltyPulse 2s infinite;
        }

        .penalty-active::after {
            content: " PENALTY";
            font-size: 10px;
            color: #FF9800;
            font-weight: bold;
            margin-left: 8px;
        }

        @keyframes penaltyPulse {

            0%,
            100% {
                border-color: #FF9800;
            }

            50% {
                border-color: #FFC107;
            }
        }

        /* Save Game Modal Styles */
        .save-game-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 8000;
        }

        .save-game-content {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            border: 3px solid #4CAF50;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
            max-width: 500px;
            width: 90%;
            animation: quarterModalAnimation 0.5s ease-out;
        }

        .save-game-title {
            font-size: 28px;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .save-game-progress {
            margin: 20px 0;
        }

        .spinner {
            border: 4px solid #333;
            border-top: 4px solid #4CAF50;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .save-status {
            font-size: 16px;
            color: #ccc;
            margin: 10px 0;
        }

        .save-error {
            color: #F44336;
            margin-top: 15px;
            padding: 10px;
            background: rgba(244, 67, 54, 0.1);
            border-radius: 8px;
        }

        .retry-btn {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .retry-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4);
        }

        /* Hotkeys Modal Styles */
        .hotkeys-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 8000;
        }

        .hotkeys-content {
            background: #2d2d2d;
            border-radius: 12px;
            padding: 30px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .hotkeys-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #444;
            padding-bottom: 15px;
        }

        .hotkeys-title {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        .hotkeys-close {
            background: #666;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
        }

        .hotkeys-close:hover {
            background: #777;
        }

        .hotkeys-instructions {
            text-align: center;
            color: #aaa;
            margin-bottom: 25px;
            padding: 12px;
            background: #333;
            border-radius: 6px;
            font-size: 14px;
        }

        .hotkeys-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .hotkey-item {
            background: #3d3d3d;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .hotkey-label {
            color: white;
            font-weight: 600;
            font-size: 14px;
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

        .hotkeys-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            padding-top: 20px;
            border-top: 1px solid #444;
        }

        .hotkey-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
        }

        .hotkey-btn.reset {
            background: linear-gradient(135deg, #666 0%, #555 100%);
            color: white;
        }

        .hotkey-btn.save {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .hotkey-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Technical Foul Indicator - Violet Circle */
        .tech-foul-indicator {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #9C27B0;
            /* Violet color */
            border: 2px solid #7B1FA2;
            box-shadow: 0 0 8px rgba(156, 39, 176, 0.6);
            z-index: 10;
            animation: techPulse 2s infinite;
        }

        @keyframes techPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        /* Make player card position relative to contain the indicator */
        .player-card {
            position: relative;
            /* Add this if not already present */
        }

        /* Pulse effect for selected action button */
        @keyframes actionPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(76, 175, 80, 0);
            }
        }

        .action-btn.selected {
            animation: actionPulse 1.5s infinite;
            outline: 3px solid #4CAF50;
            outline-offset: 3px;
        }

        /* Pulse effect for player cards when action is selected */
        @keyframes playerPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(255, 152, 0, 0);
            }
        }

        .player-card.pulse-effect {
            animation: playerPulse 1.5s infinite;
            border-color: #FF9800 !important;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
        }

        /* Toast Notification Styles */
        .toast-notification {
            position: fixed;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 8px 24px rgba(255, 152, 0, 0.4);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: toastSlideIn 0.4s ease-out;
        }

        .toast-notification.success {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            box-shadow: 0 8px 24px rgba(76, 175, 80, 0.4);
        }

        .toast-notification.error {
            background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
            box-shadow: 0 8px 24px rgba(244, 67, 54, 0.4);
        }

        .toast-notification i {
            font-size: 1.5rem;
        }

        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        @keyframes toastSlideOut {
            from {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }

            to {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
        }

        /* Quarter Summary Modal */
        .quarter-summary-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9000;
            overflow-y: auto;
        }

        .quarter-summary-content {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            border-radius: 20px;
            padding: 2rem;
            width: 95%;
            max-width: 1200px;
            max-height: 90vh;
            overflow-y: auto;
            border: 3px solid #4CAF50;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
            animation: quarterModalAnimation 0.5s ease-out;
        }

        .summary-header {
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid #444;
            padding-bottom: 1.5rem;
        }

        .summary-title {
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .summary-subtitle {
            font-size: 1.2rem;
            color: #ccc;
        }

        /* Score Summary Section */
        .score-summary {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 2rem;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 12px;
            border: 2px solid rgba(76, 175, 80, 0.3);
        }

        .team-score-box {
            text-align: center;
        }

        .team-score-name {
            font-size: 1.2rem;
            color: #ccc;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .team-score-value {
            font-size: 3rem;
            font-weight: bold;
            color: white;
            font-family: 'Courier New', monospace;
        }

        .team-score-value.winning {
            color: #4CAF50;
            text-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }

        .score-divider {
            font-size: 3rem;
            color: #666;
            font-weight: bold;
        }

        /* Quarter-by-Quarter Breakdown */
        .quarter-breakdown {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .quarter-box {
            background: #333;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #444;
        }

        .quarter-box.current {
            border-color: #4CAF50;
            background: rgba(76, 175, 80, 0.1);
        }

        .quarter-box-title {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quarter-box-scores {
            display: flex;
            justify-content: space-around;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }

        /* Stats Tables */
        .stats-section {
            margin-bottom: 2rem;
        }

        .stats-section-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: white;
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .stats-table thead {
            background: #333;
        }

        .stats-table th {
            padding: 0.75rem;
            text-align: left;
            color: #4CAF50;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #444;
        }

        .stats-table td {
            padding: 0.75rem;
            color: white;
            border-bottom: 1px solid #333;
        }

        .stats-table tbody tr:hover {
            background: rgba(76, 175, 80, 0.1);
        }

        .player-number-cell {
            font-weight: bold;
            color: #4CAF50;
        }

        .stat-value {
            text-align: center;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }

        .stat-value.highlight {
            color: #FF9800;
            font-weight: bold;
        }

        .no-stats-message {
            text-align: center;
            padding: 2rem;
            color: #888;
            font-style: italic;
        }

        /* Continue Button */
        .continue-btn {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 2rem auto 0;
            padding: 1.25rem 2rem;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .continue-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quarter-summary-content {
                padding: 1.5rem;
            }

            .summary-title {
                font-size: 1.5rem;
            }

            .score-summary {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .score-divider {
                display: none;
            }

            .team-score-value {
                font-size: 2.5rem;
            }

            .stats-table {
                font-size: 0.85rem;
            }

            .stats-table th,
            .stats-table td {
                padding: 0.5rem 0.25rem;
            }
        }

        /* Technical Foul Modal Styles */
        .tech-foul-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 3500;
        }

        .tech-foul-content {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            border-radius: 16px;
            padding: 35px;
            width: 90%;
            max-width: 900px;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            border: 3px solid #9C27B0;
            box-shadow: 0 10px 40px rgba(156, 39, 176, 0.5);
        }

        .tech-foul-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #9C27B0;
            padding-bottom: 15px;
        }

        .tech-foul-title {
            font-size: 26px;
            font-weight: bold;
            color: #9C27B0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .tech-foul-close {
            background: #666;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.2s;
        }

        .tech-foul-close:hover {
            background: #777;
            transform: scale(1.05);
        }

        /* Tech Foul Steps */
        .tech-foul-step {
            display: none;
        }

        .tech-foul-step.active {
            display: block;
            animation: fadeInStep 0.3s ease-in;
        }

        @keyframes fadeInStep {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tech-foul-step h3 {
            color: #9C27B0;
            text-align: center;
            margin-bottom: 25px;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Tech Foul Type Selection */
        .tech-type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .tech-type-card {
            background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
            padding: 25px 20px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid transparent;
            text-align: center;
        }

        .tech-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(156, 39, 176, 0.4);
            border-color: #CE93D8;
        }

        .tech-type-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .tech-type-title {
            font-size: 16px;
            font-weight: bold;
            color: white;
            margin-bottom: 8px;
        }

        .tech-type-desc {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.4;
        }

        /* Tech Consequence Selection */
        .tech-consequence-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            max-width: 700px;
            margin: 0 auto;
        }

        .tech-consequence-card {
            background: #3d3d3d;
            padding: 30px 20px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid transparent;
            text-align: center;
        }

        .tech-consequence-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .tech-consequence-card.free-throws {
            border-color: #4CAF50;
        }

        .tech-consequence-card.free-throws:hover {
            background: rgba(76, 175, 80, 0.1);
            border-color: #66BB6A;
        }

        .tech-consequence-card.ejection {
            border-color: #F44336;
        }

        .tech-consequence-card.ejection:hover {
            background: rgba(244, 67, 54, 0.1);
            border-color: #EF5350;
        }

        .consequence-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .consequence-title {
            font-size: 18px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }

        .consequence-desc {
            font-size: 13px;
            color: #aaa;
            line-height: 1.5;
        }

        /* Tech Foul Info Display */
        .tech-foul-info-box {
            background: rgba(156, 39, 176, 0.1);
            border: 2px solid #9C27B0;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            text-align: center;
        }

        .tech-foul-info-box .info-label {
            color: #CE93D8;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .tech-foul-info-box .info-value {
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        /* Player Selection for Tech Foul */
        .tech-players-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
        }

        .tech-player-card {
            background: #4d4d4d;
            padding: 15px 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            border: 3px solid transparent;
            text-align: center;
        }

        .tech-player-card:hover {
            background: #5d5d5d;
            transform: translateY(-3px);
            border-color: #9C27B0;
            box-shadow: 0 6px 20px rgba(156, 39, 176, 0.3);
        }

        .tech-player-number {
            font-size: 20px;
            font-weight: bold;
            color: white;
            margin-bottom: 5px;
        }

        .tech-player-name {
            font-size: 12px;
            color: #ddd;
            margin-bottom: 4px;
        }

        .tech-player-position {
            font-size: 11px;
            color: #aaa;
        }

        /* Ejection Substitution Section */
        .ejection-sub-section {
            margin-top: 20px;
            padding: 20px;
            background: rgba(244, 67, 54, 0.1);
            border: 2px solid #F44336;
            border-radius: 10px;
        }

        .ejection-sub-title {
            color: #F44336;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .ejection-info {
            text-align: center;
            color: #EF5350;
            font-size: 14px;
            margin-bottom: 20px;
            padding: 10px;
            background: rgba(244, 67, 54, 0.2);
            border-radius: 6px;
        }

        .sub-player-status.fouled-out {
    background: #F44336;
    color: white;
}

.sub-player-card[data-ejected="true"] {
    opacity: 0.4;
    cursor: not-allowed !important;
    background: #2a1a1a !important;
    border: 2px solid #F44336 !important;
}

.sub-player-card[data-ejected="true"]:hover {
    transform: none !important;
}
    </style>
</head>

<body>

    <!-- Timeout Instruction -->
    <div class="timeout-instruction" id="timeoutInstruction">
        Click on a team to start their timeout
    </div>

    <!-- Updated Scoreboard with Hamburger Menu -->
    <div class="scoreboard">
        <!-- Hamburger Menu (volleyball-style) -->
        <div class="menu-container" id="hamburgerMenu">
            <button class="hamburger-btn" id="hamburgerBtn" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="menu-dropdown" id="dropdownMenu">
                <a href="/tournaments/{{ $game->tournament_id }}" class="menu-item">
                    <span class="menu-item-icon">🏆</span>
                    <span class="menu-item-text">Back to Tournament</span>
                </a>
                <button class="menu-item" id="tallysheetBtn">
                    <span class="menu-item-icon">🧾</span>
                    <span class="menu-item-text">Tallysheet</span>
                </button>
                <button class="menu-item" id="hotkeysBtn">
                    <span class="menu-item-icon">⌨️</span>
                    <span class="menu-item-text">Customize Hotkeys</span>
                </button>
                <button class="menu-item" id="gameSettingsBtn">
                    <span class="menu-item-icon">⚙️</span>
                    <span class="menu-item-text">Game Settings</span>
                </button>
            </div>
        </div>

        <!-- Team A Section -->


        <!-- Center Panel -->
        <div class="center-panel">

            <div class="team-section left" id="teamSectionA" data-team="A">
                <div class="team-info">
                    <div class="team-name" id="teamAName">{{ strtoupper($game->team1->team_name) }}</div>
                    <div class="team-stats">
                        <span class="stat-item">F <span id="foulsA">0</span></span>
                        <span class="stat-item">T.O <span id="timeoutsA">0</span>/<span
                                class="max-timeouts">2</span></span>
                    </div>
                </div>
                <div class="score-display" id="scoreA">08</div>
            </div>
            <div class="period-info">

                <div class="period" id="periodDisplay"></div>
                <div class="shot-clock" id="shotClock">24</div>
            </div>
            <div class="timer-section">
                <!-- play button moved to far right of scoreboard -->
                <!-- Left Possession Arrow -->
                <div class="possession-arrow active" id="possessionLeft" data-team="A" title="Team A Possession">◀
                </div>
                <div class="timer" id="timer">08:43</div>
                <!-- Right Possession Arrow -->
                <div class="possession-arrow" id="possessionRight" data-team="B" title="Team B Possession">▶</div>
            </div>
            <div class="team-section right" id="teamSectionB" data-team="B">

                <div class="team-info">
                    <div class="team-name" id="teamBName">{{ strtoupper($game->team2->team_name) }}</div>
                    <div class="team-stats">
                        <span class="stat-item">T.O <span id="timeoutsB">0</span>/<span
                                class="max-timeouts">2</span></span>
                        <span class="stat-item">F <span id="foulsB">0</span></span>

                    </div>
                </div>
                <div class="score-display" id="scoreB">23</div>
            </div>
        </div>

        <!-- Team B Section -->

        <!-- Play/Pause at far right -->
        <button class="play-btn play-btn-right" id="playPause">▶</button>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Player Roster -->
        <div class="roster-section">
            <div class="roster-header">
                <div class="team-tab team-a active" data-team="A">{{($game->team1->team_name)}}</div>
                <div class="team-tab team-b" data-team="B">{{($game->team2->team_name)}}</div>
            </div>
            <div class="players-grid" id="playersGrid">
                <!-- Team A -->
                <div></div>
                <!-- Team B -->
                <div></div>
            </div>
        </div>


        <!-- Event Log -->
        <div class="log-section">
            <div class="log-header">Game Events</div>
            <div class="log-content" id="logContent"></div>
        </div>

        {{-- ROLE-BASED UI SECTIONS --}}

        {{-- Show this if user is ALL-IN-ONE or SCORER --}}
        @if ($userRole === 'all_in_one' || $userRole === 'scorer')
            <!-- SCORER UI - Scoring Actions -->
            <div class="actions-section" id="scorerActions">
                @if ($userRole === 'all_in_one')
                    <div class="actions-header">⚡ ALL ACTIONS</div>
                @else
                    <div class="actions-header">⚡ SCORER PANEL</div>
                @endif

                <button class="action-btn free-throw" data-action="Free Throw" data-points="1">Free Throw</button>
                <button class="action-btn two-points" data-action="2 Points" data-points="2">2 Points</button>
                <button class="action-btn three-points" data-action="3 Points" data-points="3">3 Points</button>
                <div class="foul-buttons-row">
                    <button class="action-btn foul" data-action="Foul">Foul</button>
                    <button class="action-btn tech" data-action="Tech Foul">Tech. F</button>
                </div>
                <button class="action-btn timeout" data-action="Timeout" id="timeoutBtn">Timeout</button>
                <button class="action-btn substitution" data-action="Substitution">Substitution</button>

                @if ($userRole === 'all_in_one')
                    {{-- Show stat buttons in all-in-one mode --}}
                    <button class="action-btn assist" data-action="Assist">Assist</button>
                    <button class="action-btn steal" data-action="Steal">Steal</button>
                    <button class="action-btn rebound" data-action="Rebound">Rebound</button>
                    <button class="action-btn blocks" data-action="blocks">Block</button>
                @endif

                <button class="action-btn undo-btn" id="undoBtn">
                    <span>↶</span> Undo
                </button>
            </div>
        @endif

        {{-- Show this if user is STAT-KEEPER --}}
        @if ($userRole === 'stat_keeper')
            <!-- STAT-KEEPER UI - Only Stat Recording Actions -->
            <div class="actions-section" id="statKeeperActions">
                <div class="actions-header">📊 STAT-KEEPER PANEL</div>
                <button class="action-btn assist" data-action="Assist">Assist</button>
                <button class="action-btn steal" data-action="Steal">Steal</button>
                <button class="action-btn rebound" data-action="Rebound">Rebound</button>
                <button class="action-btn blocks" data-action="blocks">Block</button>
                <button class="action-btn undo-btn" id="undoBtn">
                    <span>↶</span> Undo
                </button>

                <div
                    style="margin-top: 20px; padding: 15px; background: #333; border-radius: 8px; font-size: 12px; color: #aaa;">
                    <strong>📌 Info:</strong> Only record player stats here. Scoring actions are handled by the Scorer.
                </div>
            </div>
        @endif
    </div>

    <!-- Free Throw Panel -->
    <div class="free-throw-panel" id="freeThrowPanel">
        <h3 style="margin:0;">Free Throw Attempts</h3>
        <div class="ft-attempts" id="ftAttempts"></div>
        <button class="ft-accept" id="ftAccept">Accept</button>
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

                    <div class="sub-section-title">Active Players</div>
                    <div class="sub-players-grid" id="activePlayersA"></div>

                    <div class="sub-section-title">Bench Players</div>
                    <div class="sub-players-grid" id="benchPlayersA"></div>
                </div>

                <!-- Team B Substitutions -->
                <div class="sub-team-section sub-team-b">
                    <div class="sub-team-title">{{ strtoupper($game->team2->team_name) }}</div>

                    <div class="sub-section-title">Active Players</div>
                    <div class="sub-players-grid" id="activePlayersB"></div>

                    <div class="sub-section-title">Bench Players</div>
                    <div class="sub-players-grid" id="benchPlayersB"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Updated Foul Modal HTML -->
    <div class="foul-modal" id="foulModal">
        <div class="foul-content">
            <div class="foul-header">
                <div class="foul-title">Foul Details</div>
                <button class="foul-close" id="foulClose">&times;</button>
            </div>

            <div class="foul-body">
                <!-- Step 1: Select Who Committed the Foul -->
                <div class="foul-step" id="foulingPlayerStep">
                    <h3>Who committed the foul?</h3>
                    <div class="foul-teams">
                        <!-- Team A Players -->
                        <div class="foul-team-section">
                            <h4 id="teamAFoulingTitle">Team A Playerss</h4>
                            <div class="foul-players-grid" id="foulingPlayersA"></div>
                        </div>

                        <!-- Team B Players -->
                        <div class="foul-team-section">
                            <h4 id="teamBFoulingTitle">Team B Players</h4>
                            <div class="foul-players-grid" id="foulingPlayersB"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Foul Type Selection -->
                <div class="foul-step" id="foulTypeStep" style="display: none;">
                    <h3>Select Foul Type</h3>
                    <div class="foul-player-info">
                        <span id="foulingPlayerInfo"></span> committed a foul
                    </div>
                    <div class="foul-options">
                        <button class="foul-option-btn" data-type="personal" data-free-throws="0">
                            Personal Foul (No Free Throws)
                        </button>
                        <button class="foul-option-btn" data-type="shooting" data-free-throws="2">
                            Shooting Foul (2 Free Throws)
                        </button>
                        <button class="foul-option-btn" data-type="shooting3" data-free-throws="3">
                            3-Point Shooting Foul (3 Free Throws)
                        </button>
                    </div>
                </div>

                <!-- Step 3: Select Fouled Player (for shooting fouls) -->
                <div class="foul-step" id="fouledPlayerStep" style="display: none;">
                    <h3>Who was fouled?</h3>
                    <div class="foul-info">
                        <span id="foulTypeInfo"></span> by <span id="foulingPlayerInfo2"></span>
                    </div>
                    <div class="foul-teams">
                        <!-- Opposing team players will be populated here -->
                        <div class="foul-team-section">
                            <h4 id="opposingTeamTitle">Opposing Team Players</h4>
                            <div class="foul-players-grid" id="fouledPlayersGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quarter End Modal -->
    <div class="quarter-modal" id="quarterModal">
        <div class="quarter-content">
            <div class="quarter-title" id="quarterTitle">1st Quarter Ended</div>
            <div class="quarter-info">Take a break and review the scores</div>

            <div class="quarter-score">
                <div class="quarter-team-score">
                    <div class="quarter-team-name" id="modalTeamA">Team A</div>
                    <div class="quarter-team-points" id="modalScoreA">00</div>
                </div>
                <div class="quarter-team-score">
                    <div class="quarter-team-name" id="modalTeamB">Team B</div>
                    <div class="quarter-team-points" id="modalScoreB">00</div>
                </div>
            </div>

            <button class="quarter-btn" id="nextQuarterBtn" onclick="continueToNextQuarter()">
                Start Next Quarter
            </button>
        </div>
    </div>

    <div class="save-game-modal" id="saveGameModal">
        <div class="save-game-content">
            <div class="save-game-title">Saving Game Results</div>
            <div class="save-game-progress">
                <div class="spinner" id="saveSpinner"></div>
                <div class="save-status" id="saveStatus">Preparing game data...</div>
                <div class="save-error" id="saveError" style="display: none;"></div>
                <button class="retry-btn" id="retryBtn" style="display: none;" onclick="retryGameSave()">
                    Retry Save
                </button>
            </div>
        </div>
    </div>

    <!-- Hotkeys Modal -->
    <div class="hotkeys-modal" id="hotkeysModal">
        <div class="hotkeys-content">
            <div class="hotkeys-header">
                <div class="hotkeys-title">Customize Hotkeys</div>
                <button class="hotkeys-close" id="hotkeysClose">&times;</button>
            </div>

            <div class="hotkeys-body">
                <div class="hotkeys-instructions">
                    Click on an action and press a key to assign a hotkey. Press ESC to clear.
                </div>

                <div class="hotkeys-grid">
                    <div class="hotkey-item">
                        <div class="hotkey-label">Free Throw</div>
                        <div class="hotkey-input" data-action="Free Throw">
                            <span class="current-key" id="key-freethrow">F</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">2 Points</div>
                        <div class="hotkey-input" data-action="2 Points">
                            <span class="current-key" id="key-2points">2</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">3 Points</div>
                        <div class="hotkey-input" data-action="3 Points">
                            <span class="current-key" id="key-3points">3</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">Assist</div>
                        <div class="hotkey-input" data-action="Assist">
                            <span class="current-key" id="key-assist">A</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">Steal</div>
                        <div class="hotkey-input" data-action="Steal">
                            <span class="current-key" id="key-steal">S</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">Rebound</div>
                        <div class="hotkey-input" data-action="Rebound">
                            <span class="current-key" id="key-rebound">R</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">Foul</div>
                        <div class="hotkey-input" data-action="Foul">
                            <span class="current-key" id="key-foul">L</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">Technical Foul</div>
                        <div class="hotkey-input" data-action="Tech Foul">
                            <span class="current-key" id="key-tech">T</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">Timeout</div>
                        <div class="hotkey-input" data-action="Timeout">
                            <span class="current-key" id="key-timeout">O</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">Substitution</div>
                        <div class="hotkey-input" data-action="Substitution">
                            <span class="current-key" id="key-substitution">U</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">Undo</div>
                        <div class="hotkey-input" data-action="Undo">
                            <span class="current-key" id="key-undo">Z</span>
                        </div>
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">Play/Pause Timer</div>
                        <div class="hotkey-input" data-action="PlayPause">
                            <span class="current-key" id="key-playpause">SPACE</span>
                        </div>
                    </div>
                </div>

                <div class="hotkeys-actions">
                    <button class="hotkey-btn reset" id="resetHotkeys">Reset to Defaults</button>
                    <button class="hotkey-btn save" id="saveHotkeys">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Game Settings Modal -->
    <div class="hotkeys-modal" id="gameSettingsModal">
        <div class="hotkeys-content">
            <div class="hotkeys-header">
                <div class="hotkeys-title">Game Settings</div>
                <button class="hotkeys-close" id="gameSettingsClose">&times;</button>
            </div>

            <div class="hotkeys-body">
                <div class="hotkeys-instructions">
                    Adjust your preferred game settings below.
                </div>

                <div class="hotkeys-grid">
                    <div class="hotkey-item">
                        <div class="hotkey-label">⏱ Time Limit (per quarter, minutes)</div>
                        <input type="number" id="quarterTimeInput" class="hotkey-input" min="1"
                            max="20" value="8">
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">⏸ Timeout Duration (seconds)</div>
                        <input type="number" id="timeoutDurationInput" class="hotkey-input" min="10"
                            max="120" value="60">
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">🛑 Timeout Limit per Quarter</div>
                        <input type="number" id="timeoutLimitInput" class="hotkey-input" min="1"
                            max="5" value="2">
                    </div>

                    <div class="hotkey-item">
                        <div class="hotkey-label">🔁 Substitutions per Quarter</div>
                        <input type="number" id="subsPerQuarterInput" class="hotkey-input" min="1"
                            max="10" value="5">
                    </div>
                </div>

                <div class="hotkeys-actions">
                    <button class="hotkey-btn save" id="saveGameSettings">Save Settings</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quarter Summary Modal -->
    <div class="quarter-summary-modal" id="quarterSummaryModal">
        <div class="quarter-summary-content">
            <div class="summary-header">
                <div class="summary-title" id="summaryQuarterTitle">Quarter 1 Summary</div>
                <div class="summary-subtitle">Review scores and player performance</div>
            </div>

            <!-- Score Summary -->
            <div class="score-summary">
                <div class="team-score-box">
                    <div class="team-score-name" id="summaryTeam1Name">Team A</div>
                    <div class="team-score-value" id="summaryTeam1Score">00</div>
                </div>
                <div class="score-divider">-</div>
                <div class="team-score-box">
                    <div class="team-score-name" id="summaryTeam2Name">Team B</div>
                    <div class="team-score-value" id="summaryTeam2Score">00</div>
                </div>
            </div>

            <!-- Quarter-by-Quarter Breakdown -->
            <div class="quarter-breakdown" id="quarterBreakdown"></div>

            <!-- Team A Stats -->
            <div class="stats-section">
                <div class="stats-section-title">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span id="statsTeam1Title">Team A Statistics</span>
                </div>
                <div class="table-responsive">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Player</th>
                                <th>PTS</th>
                                <th>FG</th>
                                <th>3PT</th>
                                <th>FT</th>
                                <th>AST</th>
                                <th>REB</th>
                                <th>STL</th>
                                <th>BLK</th>
                                <th>PF</th>
                            </tr>
                        </thead>
                        <tbody id="team1StatsBody">
                            <!-- Stats will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Team B Stats -->
            <div class="stats-section">
                <div class="stats-section-title">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span id="statsTeam2Title">Team B Statistics</span>
                </div>
                <div class="table-responsive">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Player</th>
                                <th>PTS</th>
                                <th>FG</th>
                                <th>3PT</th>
                                <th>FT</th>
                                <th>AST</th>
                                <th>REB</th>
                                <th>STL</th>
                                <th>BLK</th>
                                <th>PF</th>
                            </tr>
                        </thead>
                        <tbody id="team2StatsBody">
                            <!-- Stats will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <button class="continue-btn" id="continueSummaryBtn">
                Continue to Next Quarter
            </button>
        </div>
    </div>

    <!-- Technical Foul Modal -->
    <div class="tech-foul-modal" id="techFoulModal">
        <div class="tech-foul-content">
            <div class="tech-foul-header">
                <div class="tech-foul-title">⚠️ Technical Foul</div>
                <button class="tech-foul-close" id="techFoulClose">&times;</button>
            </div>

            <!-- Step 1: Select Tech Foul Type -->
            <div class="tech-foul-step active" id="techTypeStep">
                <h3>Select Technical Foul Type</h3>
                <div class="tech-type-grid">
                    <div class="tech-type-card" data-type="unsportsmanlike">
                        <div class="tech-type-icon">🤬</div>
                        <div class="tech-type-title">Unsportsmanlike Behavior</div>
                        <div class="tech-type-desc">Taunting, excessive arguing, or disrespectful conduct</div>
                    </div>
                    <div class="tech-type-card" data-type="bench">
                        <div class="tech-type-icon">👥</div>
                        <div class="tech-type-title">Bench/Coach Violations</div>
                        <div class="tech-type-desc">Coach misconduct, illegal bench participation</div>
                    </div>
                    <div class="tech-type-card" data-type="administrative">
                        <div class="tech-type-icon">📋</div>
                        <div class="tech-type-title">Administrative</div>
                        <div class="tech-type-desc">Delay of game, illegal substitution, violations</div>
                    </div>
                    <div class="tech-type-card" data-type="other">
                        <div class="tech-type-icon">⚠️</div>
                        <div class="tech-type-title">Other Technicals</div>
                        <div class="tech-type-desc">Hanging on rim, fighting, or other infractions</div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Select Player Who Committed Tech -->
            <div class="tech-foul-step" id="techPlayerStep">
                <div class="tech-foul-info-box">
                    <div class="info-label">Selected Type</div>
                    <div class="info-value" id="selectedTechType">-</div>
                </div>
                <h3>Who Committed the Technical Foul?</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <!-- Team A -->
                    <div>
                        <h4 style="color: #c33; text-align: center; margin-bottom: 15px;" id="techTeamATitle">Team A
                        </h4>
                        <div class="tech-players-grid" id="techPlayersA"></div>
                    </div>
                    <!-- Team B -->
                    <div>
                        <h4 style="color: #339; text-align: center; margin-bottom: 15px;" id="techTeamBTitle">Team B
                        </h4>
                        <div class="tech-players-grid" id="techPlayersB"></div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Choose Consequence -->
            <div class="tech-foul-step" id="techConsequenceStep">
                <div class="tech-foul-info-box">
                    <div class="info-label">Player Committing Foul</div>
                    <div class="info-value" id="selectedTechPlayer">-</div>
                </div>
                <h3>Select Consequence</h3>
                <div class="tech-consequence-grid">
                    <div class="tech-consequence-card free-throws" id="techFreeThrowsOption">
                        <div class="consequence-icon">🏀</div>
                        <div class="consequence-title">2 Free Throws</div>
                        <div class="consequence-desc">Opposing team shoots 2 free throws. Player remains in game.</div>
                    </div>
                    <div class="tech-consequence-card ejection" id="techEjectionOption">
                        <div class="consequence-icon">🚫</div>
                        <div class="consequence-title">Ejection</div>
                        <div class="consequence-desc">Player is ejected from the game. Opposing team shoots 2 free
                            throws. Requires substitution.</div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Ejection Substitution (if ejection chosen) -->
            <div class="tech-foul-step" id="techEjectionSubStep">
                <div class="ejection-info">
                    <strong id="ejectedPlayerInfo">Player #X</strong> has been ejected from the game
                </div>
                <div class="ejection-sub-section">
                    <div class="ejection-sub-title">Select Replacement Player</div>
                    <div class="tech-players-grid" id="techSubstitutePlayers"></div>
                </div>
            </div>
        </div>
    </div>




    <script>
        const gameData = {
    id: {{ $game->id }},
    team1: {
        name: '{{ $game->team1->team_name }}',
        players: @json($team1PlayersArray ?? []),
        roster: @json($team1Roster ?? []),
        starters: @json($team1Starters ?? [])
    },
    team2: {
        name: '{{ $game->team2->team_name }}',
        players: @json($team2PlayersArray ?? []),
        roster: @json($team2Roster ?? []),
        starters: @json($team2Starters ?? [])
    },
    referee: '{{ $game->referee }}',
    startedAt: '{{ $game->started_at }}',
    tournamentId: {{ $game->tournament_id ?? 'null' }},
    bracketId: {{ $game->bracket_id ?? 'null' }}
};

// ✅ DEBUG LOG
console.log('🎮 Game Data Loaded:', gameData);
console.log('👥 Team 1 Players:', gameData.team1.players);
console.log('👥 Team 2 Players:', gameData.team2.players);
console.log('📋 User Role:', '{{ $userRole }}');

// ✅ ADD THIS SECTION
    const userRole = '{{ $userRole }}';
    console.log('👤 Current User Role:', userRole);

    // ✅ Helper function to safely add event listeners
    function safeAddEventListener(elementId, event, handler) {
        const element = document.getElementById(elementId);
        if (element) {
            element.addEventListener(event, handler);
            console.log(`✅ Event listener attached to: ${elementId}`);
        } else {
            console.log(`⚠️ Element not found (skipped): ${elementId}`);
        }
    }

        // Calculate player stats from game events
        function calculatePlayerStats() {
            const stats = {
                A: {},
                B: {}
            };

            // Initialize stats for all players
            [...activePlayers.A, ...benchPlayers.A].forEach(player => {
                const playerId = player.id;
                stats.A[playerId] = {
                    number: player.number || '00',
                    name: player.name,
                    points: 0,
                    fieldGoalsMade: 0,
                    fieldGoalsAttempted: 0,
                    threePointsMade: 0,
                    threePointsAttempted: 0,
                    freeThrowsMade: 0,
                    freeThrowsAttempted: 0,
                    assists: 0,
                    rebounds: 0,
                    steals: 0,
                    blocks: 0,
                    fouls: player.fouls || 0
                };
            });

            [...activePlayers.B, ...benchPlayers.B].forEach(player => {
                const playerId = player.id;
                stats.B[playerId] = {
                    number: player.number || '00',
                    name: player.name,
                    points: 0,
                    fieldGoalsMade: 0,
                    fieldGoalsAttempted: 0,
                    threePointsMade: 0,
                    threePointsAttempted: 0,
                    freeThrowsMade: 0,
                    freeThrowsAttempted: 0,
                    assists: 0,
                    rebounds: 0,
                    steals: 0,
                    blocks: 0,
                    fouls: player.fouls || 0
                };
            });

            // Process game events
            gameEvents.forEach(event => {
                if (event.player === 'TEAM' || event.player === 'SYSTEM') return;

                const team = event.team;
                const playerNumber = event.player;

                // Find player by number
                const playerArrays = team === 'A' ?
                    [...activePlayers.A, ...benchPlayers.A] :
                    [...activePlayers.B, ...benchPlayers.B];

                const player = playerArrays.find(p => (p.number || '00').toString() === playerNumber.toString());
                if (!player) return;

                const playerId = player.id;
                const action = event.action;

                // Points
                if (action.includes('Points') || action.includes('Made')) {
                    stats[team][playerId].points += event.points || 0;
                }

                // Field Goals (2-pointers)
                if (action === '2 Points') {
                    stats[team][playerId].fieldGoalsMade++;
                    stats[team][playerId].fieldGoalsAttempted++;
                }

                // Three Pointers
                if (action === '3 Points') {
                    stats[team][playerId].threePointsMade++;
                    stats[team][playerId].threePointsAttempted++;
                }

                // Free Throws
                if (action.includes('Free Throw')) {
                    if (action.includes('Made')) {
                        stats[team][playerId].freeThrowsMade++;
                        stats[team][playerId].freeThrowsAttempted++;
                    } else if (action.includes('Miss')) {
                        stats[team][playerId].freeThrowsAttempted++;
                    }
                }

                // Assists
                if (action === 'Assist') {
                    stats[team][playerId].assists++;
                }

                // Rebounds
                if (action === 'Rebound') {
                    stats[team][playerId].rebounds++;
                }

                // Steals
                if (action === 'Steal') {
                    stats[team][playerId].steals++;
                }

                // Blocks
                if (action === 'blocks') {
                    stats[team][playerId].blocks++;
                }
            });

            return stats;
        }

        // Show quarter summary modal
        function showQuarterSummary(quarter) {
            const modal = document.getElementById('quarterSummaryModal');
            const quarterTitle = document.getElementById('summaryQuarterTitle');
            const team1Name = document.getElementById('summaryTeam1Name');
            const team2Name = document.getElementById('summaryTeam2Name');
            const team1Score = document.getElementById('summaryTeam1Score');
            const team2Score = document.getElementById('summaryTeam2Score');
            const quarterBreakdown = document.getElementById('quarterBreakdown');
            const continueBtn = document.getElementById('continueSummaryBtn');

            // Update title
            const quarterNames = ['', '1st', '2nd', '3rd', '4th'];
            quarterTitle.textContent = `${quarterNames[quarter]} Quarter Summary`;

            // Update team names
            team1Name.textContent = gameData.team1.name;
            team2Name.textContent = gameData.team2.name;

            // Update scores
            team1Score.textContent = scoreA.toString().padStart(2, '0');
            team2Score.textContent = scoreB.toString().padStart(2, '0');

            // Highlight winning team
            team1Score.classList.remove('winning');
            team2Score.classList.remove('winning');
            if (scoreA > scoreB) {
                team1Score.classList.add('winning');
            } else if (scoreB > scoreA) {
                team2Score.classList.add('winning');
            }

            // Generate quarter breakdown
            quarterBreakdown.innerHTML = '';
            for (let q = 1; q <= quarter; q++) {
                const quarterBox = document.createElement('div');
                quarterBox.className = `quarter-box ${q === quarter ? 'current' : ''}`;
                quarterBox.innerHTML = `
            <div class="quarter-box-title">Quarter ${q}</div>
            <div class="quarter-box-scores">
                <span>${periodScores.teamA[q - 1] || 0}</span>
                <span>-</span>
                <span>${periodScores.teamB[q - 1] || 0}</span>
            </div>
        `;
                quarterBreakdown.appendChild(quarterBox);
            }

            // Calculate and display stats
            const stats = calculatePlayerStats();
            populateStatsTable('team1StatsBody', stats.A, 'A');
            populateStatsTable('team2StatsBody', stats.B, 'B');

            // Update stats titles
            document.getElementById('statsTeam1Title').textContent = `${gameData.team1.name} Statistics`;
            document.getElementById('statsTeam2Title').textContent = `${gameData.team2.name} Statistics`;

            // Show modal
            modal.style.display = 'flex';

            // Update continue button
            if (quarter < maxQuarters) {
                continueBtn.textContent = `Continue to ${quarterNames[quarter + 1]} Quarter`;
                continueBtn.onclick = () => {
                    modal.style.display = 'none';
                    startNextQuarter();
                };
            } else {
                continueBtn.textContent = 'Finish Game';
                continueBtn.onclick = () => {
                    modal.style.display = 'none';
                    showGameEndModal();
                };
            }
        }

        // Populate stats table
        function populateStatsTable(tableBodyId, teamStats, team) {
            const tbody = document.getElementById(tableBodyId);
            tbody.innerHTML = '';

            // Convert stats object to array and sort by points
            const statsArray = Object.values(teamStats).sort((a, b) => b.points - a.points);

            if (statsArray.length === 0) {
                tbody.innerHTML = `
            <tr>
                <td colspan="11" class="no-stats-message">
                    No statistics recorded yet
                </td>
            </tr>
        `;
                return;
            }

            statsArray.forEach(player => {
                const row = document.createElement('tr');

                // Determine field goal percentage
                const fgPercentage = player.fieldGoalsAttempted > 0 ?
                    `${player.fieldGoalsMade}/${player.fieldGoalsAttempted}` :
                    '0/0';

                const threePercentage = player.threePointsAttempted > 0 ?
                    `${player.threePointsMade}/${player.threePointsAttempted}` :
                    '0/0';

                const ftPercentage = player.freeThrowsAttempted > 0 ?
                    `${player.freeThrowsMade}/${player.freeThrowsAttempted}` :
                    '0/0';

                // Highlight leading stats
                const maxPoints = Math.max(...statsArray.map(p => p.points));
                const pointsClass = player.points === maxPoints && player.points > 0 ? 'highlight' : '';

                row.innerHTML = `
            <td class="player-number-cell">${player.number}</td>
            <td>${player.name}</td>
            <td class="stat-value ${pointsClass}">${player.points}</td>
            <td class="stat-value">${fgPercentage}</td>
            <td class="stat-value">${threePercentage}</td>
            <td class="stat-value">${ftPercentage}</td>
            <td class="stat-value">${player.assists}</td>
            <td class="stat-value">${player.rebounds}</td>
            <td class="stat-value">${player.steals}</td>
            <td class="stat-value">${player.blocks}</td>
            <td class="stat-value">${player.fouls}</td>
        `;

                tbody.appendChild(row);
            });
        }

        // Determine which role this user has
        const userRole = '{{ $userRole ?? 'viewer' }}';

        // Disable actions based on role
        function applyRoleRestrictions() {
            console.log('🔐 Applying role restrictions for:', userRole);

            // ✅ NEW: If all-in-one mode, show ALL buttons
            if (userRole === 'all_in_one') {
                console.log('✅ All-in-one mode: All actions enabled');
                return; // Don't disable anything
            }

            if (userRole === 'scorer') {
                // Scorer: disable stat buttons
                const statOnlyButtons = ['Assist', 'Steal', 'Rebound', 'blocks'];
                disableButtons(statOnlyButtons);
                console.log('✅ Scorer mode: Stat buttons disabled');

            } else if (userRole === 'stat_keeper') {
                // Stat-keeper: disable scoring buttons
                const scoringOnlyButtons = [
                    'Free Throw', '2 Points', '3 Points',
                    'Foul', 'Tech Foul', 'Timeout', 'Substitution'
                ];
                disableButtons(scoringOnlyButtons);
                console.log('✅ Stat-keeper mode: Scoring buttons disabled');
            }
        }

        // Helper function to disable buttons
        function disableButtons(actionNames) {
            document.querySelectorAll('.action-btn').forEach(btn => {
                const action = btn.dataset.action;
                if (actionNames.includes(action)) {
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                    btn.title = `Not available for ${userRole}`;
                }
            });
        }



        // Toast notification system
        function showToast(message, type = 'info', duration = 3000) {
            // Remove any existing toast
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }

            // Create new toast
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;

            // Add icon based on type
            let icon = '📌';
            if (type === 'success') icon = '✓';
            if (type === 'error') icon = '⚠';
            if (type === 'info') icon = 'ℹ️';

            toast.innerHTML = `
        <i>${icon}</i>
        <span>${message}</span>
    `;

            document.body.appendChild(toast);

            // Auto-remove after duration
            setTimeout(() => {
                toast.style.animation = 'toastSlideOut 0.4s ease-out';
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 400);
            }, duration);
        }

        // Override the logEvent function to enforce role-based actions
        const originalLogEvent = window.logEvent;
        window.logEvent = function(team, player, action, points = 0) {
            // ✅ NEW: Skip role checks in all-in-one mode
            if (userRole === 'all_in_one') {
                return originalLogEvent(team, player, action, points);
            }

            // Check if this action is allowed for this role
            const scoringActions = ['Free Throw', '2 Points', '3 Points', 'Foul', 'Tech Foul', 'Timeout',
                'Substitution', 'Personal Foul', 'Shooting Foul'
            ];
            const statActions = ['Assist', 'Steal', 'Rebound', 'blocks'];

            if (userRole === 'scorer' && statActions.includes(action)) {
                console.warn('⛔ Scorer cannot record stat action:', action);
                alert('⛔ Your role (Scorer) cannot record this stat. Only Stat-Keepers can record stats.');
                return;
            }

            if (userRole === 'stat_keeper' && scoringActions.includes(action)) {
                console.warn('⛔ Stat-keeper cannot record scoring action:', action);
                alert('⛔ Your role (Stat-Keeper) cannot record scoring. Only Scorers can record scores and fouls.');
                return;
            }

            // If role check passes, call original logEvent
            return originalLogEvent(team, player, action, points);
        };

        // Apply restrictions when page loads
        document.addEventListener('DOMContentLoaded', applyRoleRestrictions);

        // Player roster management - UPDATED to use actual starter/roster data
        let activePlayers = {
            A: [],
            B: []
        };

        let benchPlayers = {
            A: [],
            B: []
        };

        // Add these variables at the top with your other game variables
        let periodScores = {
            teamA: [0, 0, 0, 0], // Quarters 1-4
            teamB: [0, 0, 0, 0]
        };

        let quarterStartScores = {
            teamA: [0, 0, 0, 0, 0], // Score at start of each quarter (0, Q1, Q2, Q3, Q4)
            teamB: [0, 0, 0, 0, 0]
        };

        // Add these functions
        function getTeamAPeriodScores() {
            return periodScores.teamA;
        }

        function getTeamBPeriodScores() {
            return periodScores.teamB;
        }

        // Initialize active and bench players based on preparation data
        function initializePlayerRosters() {
    console.log('🔧 Initializing player rosters...');
    console.log('Team 1 starters:', gameData.team1.starters);
    console.log('Team 1 all players:', gameData.team1.players);
    
    // Team A (team1)
    if (gameData.team1.starters && gameData.team1.starters.length > 0) {
        // Use the starters selected in preparation
        gameData.team1.players.forEach(player => {
            if (gameData.team1.starters.includes(player.id.toString())) {
                activePlayers.A.push(player);
                console.log('✅ Added Team A starter:', player.name);
            } else if (gameData.team1.roster && gameData.team1.roster.includes(player.id.toString())) {
                benchPlayers.A.push(player);
                console.log('📋 Added Team A bench:', player.name);
            }
        });
    } else {
        // Fallback to first 5 if no preparation data
        activePlayers.A = gameData.team1.players.slice(0, 5);
        benchPlayers.A = gameData.team1.players.slice(5);
        console.log('⚠️ Using fallback for Team A');
    }

    // Team B (team2)
    if (gameData.team2.starters && gameData.team2.starters.length > 0) {
        gameData.team2.players.forEach(player => {
            if (gameData.team2.starters.includes(player.id.toString())) {
                activePlayers.B.push(player);
                console.log('✅ Added Team B starter:', player.name);
            } else if (gameData.team2.roster && gameData.team2.roster.includes(player.id.toString())) {
                benchPlayers.B.push(player);
                console.log('📋 Added Team B bench:', player.name);
            }
        });
    } else {
        // Fallback
        activePlayers.B = gameData.team2.players.slice(0, 5);
        benchPlayers.B = gameData.team2.players.slice(5);
        console.log('⚠️ Using fallback for Team B');
    }

    console.log('✅ Roster initialization complete');
    console.log('Team A Active:', activePlayers.A);
    console.log('Team A Bench:', benchPlayers.A);
    console.log('Team B Active:', activePlayers.B);
    console.log('Team B Bench:', benchPlayers.B);
}

        function initializePlayerFouls() {
            // Initialize foul count for all players
            [...activePlayers.A, ...benchPlayers.A, ...activePlayers.B, ...benchPlayers.B].forEach(player => {
                player.fouls = 0;
            });
        }

        // Initialize game variables
        let scoreA = 0,
            scoreB = 0;
        let foulsA = 0,
            foulsB = 0;
        let timeoutsA = 0,
            timeoutsB = 0;
        let eventCounter = 1;
        let gameEvents = [];
        // Ball possession tracking
        let currentPossession = 'A'; // Team A starts with possession

        const scoreADisplay = document.getElementById("scoreA");
        const scoreBDisplay = document.getElementById("scoreB");
        const foulsADisplay = document.getElementById("foulsA");
        const foulsBDisplay = document.getElementById("foulsB");
        const timeoutsADisplay = document.getElementById("timeoutsA");
        const timeoutsBDisplay = document.getElementById("timeoutsB");
        const logContent = document.getElementById("logContent");
        const leaderArrow = document.getElementById("leaderArrow");



        let timerDisplay = document.getElementById("timer");
        let playPauseBtn = document.getElementById("playPause");
        let isRunning = false;
        let time = 20; // 8 minutes in seconds
        let interval;
        let wasRunningBeforePause = false;
        let pauseReason = null;
        // Add after: let pauseReason = null;
        let currentQuarter = 1;
        let maxQuarters = 4;
        let quarterLength = 20; // 8 minutes per quarter

        // Get period display element
        const periodDisplay = document.querySelector('.period');

        // Shot clock variables
        const shotClockDisplay = document.getElementById('shotClock');
        let shotTime = 24; // seconds
        let shotInterval = null;
        let isShotRunning = false;

        function updateShotClockDisplay() {
            if (shotClockDisplay) {
                shotClockDisplay.textContent = shotTime.toString().padStart(2, '0');
                // change color near end
                if (shotTime <= 5) {
                    shotClockDisplay.style.background = '#ff7043';
                    shotClockDisplay.style.boxShadow = '0 6px 16px rgba(255,112,67,0.25)';
                } else {
                    shotClockDisplay.style.background = '#e53935';
                    shotClockDisplay.style.boxShadow = '0 4px 10px rgba(229,57,53,0.2)';
                }
            }
        }

        function startShotClock() {
            if (!shotClockDisplay) return;
            if (shotInterval) clearInterval(shotInterval);
            isShotRunning = true;
            shotInterval = setInterval(() => {
                if (shotTime > 0) {
                    shotTime--;
                    updateShotClockDisplay();
                } else {
                    clearInterval(shotInterval);
                    isShotRunning = false;
                    // Auto-pause game on shot-clock violation
                    pauseTimer('shot clock');
                    // Log shot-clock violation
                    logEvent('GAME', 'SYSTEM', 'Shot Clock Violation', 0);
                }
            }, 1000);
        }

        function pauseShotClock() {
            if (shotInterval) {
                clearInterval(shotInterval);
                shotInterval = null;
            }
            isShotRunning = false;
        }

        function resetShotClock(seconds = 24) {
            shotTime = seconds;
            updateShotClockDisplay();
        }

        // Timeout-related variables
        let timeoutMode = false;
        let timeoutTimer = null;
        let timeoutTime = 60; // 1 minute in seconds
        const timeoutBtn = document.getElementById('timeoutBtn');
        const timeoutInstruction = document.getElementById('timeoutInstruction');
        const teamSectionA = document.getElementById('teamSectionA');
        const teamSectionB = document.getElementById('teamSectionB');

        // Initialize displays
        scoreADisplay.textContent = scoreA.toString().padStart(2, '0');
        scoreBDisplay.textContent = scoreB.toString().padStart(2, '0');
        // initialize shot clock display
        updateShotClockDisplay();

        // Enhanced Timer functionality with auto-pause
        function updateTimer() {
            let minutes = Math.floor(time / 60);
            let seconds = time % 60;
            timerDisplay.textContent = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
            updateTimerStatus();

            // Check if quarter ended
            if (time <= 0 && currentQuarter <= maxQuarters) {
                handleQuarterEnd();
            }
        }

        // UPDATED: Modified handleQuarterEnd to ensure proper game completion
        function handleQuarterEnd() {
            // Calculate current quarter score before ending
            if (currentQuarter <= 4) {
                const startScore = currentQuarter === 1 ? 0 : quarterStartScores.teamA[currentQuarter - 1];
                periodScores.teamA[currentQuarter - 1] = scoreA - startScore;
                const startScoreB = currentQuarter === 1 ? 0 : quarterStartScores.teamB[currentQuarter - 1];
                periodScores.teamB[currentQuarter - 1] = scoreB - startScoreB;
            }

            // Pause the timer
            if (isRunning) {
                clearInterval(interval);
                isRunning = false;
                playPauseBtn.textContent = "▶";
            }

            // Pause and reset shot clock at quarter end
            pauseShotClock();
            resetShotClock(24);

            // Log quarter end event
            logEvent('GAME', 'SYSTEM', `Quarter ${currentQuarter} Ended`, 0);

            // ✅ SHOW QUARTER SUMMARY INSTEAD OF SIMPLE MODAL
            showQuarterSummary(currentQuarter);
        }

        function showOvertimeModal() {
            const modal = document.getElementById('quarterModal');
            const title = document.getElementById('quarterTitle');
            const info = document.querySelector('.quarter-info');
            const teamA = document.getElementById('modalTeamA');
            const teamB = document.getElementById('modalTeamB');
            const scoreAElement = document.getElementById('modalScoreA');
            const scoreBElement = document.getElementById('modalScoreB');
            const nextBtn = document.getElementById('nextQuarterBtn');

            // Update modal content for overtime
            title.textContent = 'GAME TIED!';
            title.style.color = '#FF9800';
            info.textContent = 'Going to Overtime - 2 Minutes';

            teamA.textContent = gameData.team1.name;
            teamB.textContent = gameData.team2.name;
            scoreAElement.textContent = scoreA.toString().padStart(2, '0');
            scoreBElement.textContent = scoreB.toString().padStart(2, '0');

            nextBtn.textContent = 'Start Overtime';
            nextBtn.style.display = 'inline-block';
            nextBtn.onclick = () => {
                modal.style.display = 'none';
                startOvertime();
            };

            modal.style.display = 'flex';
        }

        function startOvertime() {
            currentQuarter++; // This will be 5 (OT1), 6 (OT2), etc.
            maxQuarters++; // Increase max quarters to allow overtime
            time = 120; // 2 minutes = 120 seconds
            updateTimer();

            // Update period display to show "OT"
            if (periodDisplay) {
                const overtimeNumber = currentQuarter - 4; // OT1, OT2, OT3, etc.
                periodDisplay.textContent = `OT${overtimeNumber}`;
            }

            logEvent('GAME', 'SYSTEM', `Overtime ${currentQuarter - 4} Started`, 0);
        }

        function showQuarterEndModal() {
            const modal = document.getElementById('quarterModal');
            const title = document.getElementById('quarterTitle');
            const teamA = document.getElementById('modalTeamA');
            const teamB = document.getElementById('modalTeamB');
            const scoreAElement = document.getElementById('modalScoreA'); // Renamed
            const scoreBElement = document.getElementById('modalScoreB'); // Renamed
            const nextBtn = document.getElementById('nextQuarterBtn');

            // Update modal content
            const quarterNames = ['', '1st', '2nd', '3rd', '4th'];
            title.textContent = `${quarterNames[currentQuarter]} Quarter Ended`;

            teamA.textContent = gameData.team1.name;
            teamB.textContent = gameData.team2.name;

            // Use the global score variables, not the DOM elements
            scoreAElement.textContent = scoreA.toString().padStart(2, '0'); // ✅ Fixed
            scoreBElement.textContent = scoreB.toString().padStart(2, '0'); // ✅ Fixed

            if (currentQuarter < maxQuarters) {
                nextBtn.textContent = `Start ${quarterNames[currentQuarter + 1]} Quarter`;
                nextBtn.style.display = 'inline-block';
            }

            modal.style.display = 'flex';
        }

        function showGameEndModal() {
            const modal = document.getElementById('quarterModal');
            const title = document.getElementById('quarterTitle');
            const info = document.querySelector('.quarter-info');
            const teamA = document.getElementById('modalTeamA');
            const teamB = document.getElementById('modalTeamB');
            const scoreAElement = document.getElementById('modalScoreA');
            const scoreBElement = document.getElementById('modalScoreB');
            const nextBtn = document.getElementById('nextQuarterBtn');

            // Update for game end
            title.textContent = 'GAME ENDED';
            title.className = 'quarter-title game-end-title';

            // Determine winner using global variables
            let winner = '';
            if (scoreA > scoreB) {
                winner = `${gameData.team1.name} Wins!`;
            } else if (scoreB > scoreA) {
                winner = `${gameData.team2.name} Wins!`;
            } else {
                winner = 'It\'s a Tie!';
            }

            info.textContent = winner;
            teamA.textContent = gameData.team1.name;
            teamB.textContent = gameData.team2.name;

            // Use global score variables
            scoreAElement.textContent = scoreA.toString().padStart(2, '0');
            scoreBElement.textContent = scoreB.toString().padStart(2, '0');

            nextBtn.textContent = 'Save Game Results';
            nextBtn.className = 'quarter-btn game-end-btn';

            // UPDATED: Modified to save game results instead of just showing alert
            nextBtn.onclick = () => {
                modal.style.display = 'none';
                saveGameResults();
            };

            modal.style.display = 'flex';
        }

        function continueToNextQuarter() {
            const modal = document.getElementById('quarterModal');
            modal.style.display = 'none';

            startNextQuarter();
        }

        // Update your startNextQuarter() function to track period scores
        function startNextQuarter() {
            // Store the score at the start of this quarter
            quarterStartScores.teamA[currentQuarter] = scoreA;
            quarterStartScores.teamB[currentQuarter] = scoreB;

            // Calculate the previous quarter's score
            if (currentQuarter > 1) {
                periodScores.teamA[currentQuarter - 2] = scoreA - quarterStartScores.teamA[currentQuarter - 1];
                periodScores.teamB[currentQuarter - 2] = scoreB - quarterStartScores.teamB[currentQuarter - 1];
            }

            currentQuarter++;
            time = quarterLength;
            updateTimer();
            updatePeriodDisplay();

            // Reset shot clock at the start of a new quarter
            resetShotClock(24);

            logEvent('GAME', 'SYSTEM', `Quarter ${currentQuarter} Started`, 0);
        }

        function updatePeriodDisplay() {
            if (periodDisplay) {
                if (currentQuarter <= 4) {
                    periodDisplay.textContent = `Q${currentQuarter}`;
                } else {
                    const overtimeNumber = currentQuarter - 4;
                    periodDisplay.textContent = `OT${overtimeNumber}`;
                }
            }
        }

        function pauseTimer(reason = 'manual') {
            if (isRunning) {
                wasRunningBeforePause = true;
                clearInterval(interval);
                isRunning = false;
                playPauseBtn.textContent = "▶";
                pauseReason = reason;

                // Add visual indicator for automatic pauses
                if (reason !== 'manual') {
                    playPauseBtn.style.backgroundColor = '#FF9800'; // Orange for auto-pause
                    playPauseBtn.title = `Timer paused for ${reason}`;
                }
            }
        }

        function resumeTimer() {
            if (!isRunning && wasRunningBeforePause) {
                interval = setInterval(() => {
                    if (time > 0) {
                        time--;
                        updateTimer();
                    }
                }, 1000);
                isRunning = true;
                playPauseBtn.textContent = "⏸";
                playPauseBtn.style.backgroundColor = '#4CAF50';
                playPauseBtn.title = '';
                wasRunningBeforePause = false;
                pauseReason = null;
            }
        }

        function showResumePrompt(message) {
            const resumeDiv = document.createElement('div');
            resumeDiv.id = 'resumePrompt';
            resumeDiv.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #333;
        color: white;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.6);
        z-index: 5000;
        text-align: center;
        border: 2px solid #FF9800;
      `;

            resumeDiv.innerHTML = `
        <div style="font-size: 16px; margin-bottom: 20px;">${message}</div>
        <div style="display: flex; gap: 15px; justify-content: center;">
          <button id="resumeYes" style="
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
          ">Resume</button>
          <button id="resumeLater" style="
            background: #666;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
          ">Keep Paused</button>
        </div>
      `;

            document.body.appendChild(resumeDiv);

            // Event listeners for resume buttons
            document.getElementById('resumeYes').onclick = () => {
                resumeTimer();
                document.body.removeChild(resumeDiv);
            };

            document.getElementById('resumeLater').onclick = () => {
                pauseReason = 'manual';
                playPauseBtn.style.backgroundColor = '#4CAF50';
                playPauseBtn.title = '';
                document.body.removeChild(resumeDiv);
            };
        }

        function updateTimerStatus() {
            const timerContainer = document.querySelector('.center-panel');
            let statusIndicator = document.getElementById('timerStatus');

            if (!statusIndicator) {
                statusIndicator = document.createElement('div');
                statusIndicator.id = 'timerStatus';
                statusIndicator.style.cssText = `
          font-size: 11px;
          color: #FF9800;
          text-align: center;
          margin-top: 4px;
          font-weight: bold;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        `;
                timerContainer.appendChild(statusIndicator);
            }

            if (pauseReason && pauseReason !== 'manual') {
                statusIndicator.textContent = `⏸ Paused (${pauseReason})`;
                statusIndicator.style.display = 'block';
            } else {
                statusIndicator.style.display = 'none';
            }
        }

        playPauseBtn.addEventListener("click", () => {
            if (isRunning) {
                // Manual pause
                wasRunningBeforePause = true;
                clearInterval(interval);
                isRunning = false;
                playPauseBtn.textContent = "▶";
                pauseReason = 'manual';
                playPauseBtn.style.backgroundColor = '#4CAF50';
                playPauseBtn.title = '';
                // Pause shot clock when game paused
                pauseShotClock();
            } else {
                if (pauseReason && pauseReason !== 'manual') {
                    // Show confirmation for resuming during auto-pause
                    if (confirm(`Timer was paused for ${pauseReason}. Resume game clock?`)) {
                        resumeTimer();
                        // Resume shot clock if it was running
                        if (shotTime > 0) {
                            startShotClock();
                        }
                    }
                } else {
                    // Normal resume
                    interval = setInterval(() => {
                        if (time > 0) {
                            time--;
                            updateTimer();
                        }
                    }, 1000);
                    isRunning = true;
                    playPauseBtn.textContent = "⏸";
                    playPauseBtn.style.backgroundColor = '#4CAF50';
                    wasRunningBeforePause = false;
                    pauseReason = null;
                    // Start or resume shot clock when game resumes
                    if (shotTime <= 0) {
                        resetShotClock(24);
                    }
                    startShotClock();
                }
            }
        });

        // NEW: Save game results function
        function saveGameResults() {
            const saveModal = document.getElementById('saveGameModal');
            const saveStatus = document.getElementById('saveStatus');
            const saveError = document.getElementById('saveError');
            const saveSpinner = document.getElementById('saveSpinner');
            const retryBtn = document.getElementById('retryBtn');

            // Show save modal
            saveModal.style.display = 'flex';
            saveStatus.textContent = 'Preparing game data...';
            saveError.style.display = 'none';
            retryBtn.style.display = 'none';
            saveSpinner.style.display = 'block';

            // NEW: Collect player statistics
            const playerStats = collectPlayerStats();

            // Prepare game data
            const finalGameData = {
                game_id: gameData.id,
                team1_score: scoreA,
                team2_score: scoreB,
                team1_fouls: foulsA,
                team2_fouls: foulsB,
                team1_timeouts: timeoutsA,
                team2_timeouts: timeoutsB,
                total_quarters: currentQuarter,
                game_events: gameEvents,
                period_scores: {
                    team1: periodScores.teamA,
                    team2: periodScores.teamB
                },
                winner_id: scoreA > scoreB ? 1 : (scoreB > scoreA ? 2 : null),
                status: 'completed',
                completed_at: new Date().toISOString(),
                player_stats: Object.values(playerStats)
            };

            // Update status
            saveStatus.textContent = 'Saving game results...';

            // Send data to backend
            fetch(`/games/${gameData.id}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(finalGameData)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    saveStatus.textContent = 'Game saved successfully!';
                    saveSpinner.style.display = 'none';

                    // Show success message briefly then redirect
                    setTimeout(() => {
                        saveStatus.textContent = 'Redirecting to box score...';
                        window.location.href = data.redirect_url;
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error saving game:', error);
                    saveSpinner.style.display = 'none';
                    saveStatus.textContent = 'Failed to save game results';
                    saveError.style.display = 'block';
                    saveError.textContent = `Error: ${error.message}. Please try again.`;
                    retryBtn.style.display = 'inline-block';
                });
        }

        

        // NEW: Collect player statistics from game events
        // Collect player statistics from game events
        function collectPlayerStats() {
            const playerStats = {};

            // Helper to get player by number and team
            function findPlayerByNumber(playerNumber, team) {
                const players = team === 'A' ? [...activePlayers.A, ...benchPlayers.A] : [...activePlayers.B, ...
                    benchPlayers.B
                ];
                return players.find(p => (p.number || '00').toString() === playerNumber.toString());
            }

            // Initialize stats for all players
            [...activePlayers.A, ...benchPlayers.A].forEach(player => {
                const key = `A_${player.id}`;
                playerStats[key] = {
                    player_id: player.id,
                    team_id: player.team_id,
                    points: 0,
                    fouls: player.fouls || 0,
                    free_throws_made: 0,
                    free_throws_attempted: 0,
                    two_points_made: 0,
                    two_points_attempted: 0,
                    three_points_made: 0,
                    three_points_attempted: 0,
                    assists: 0,
                    steals: 0,
                    rebounds: 0,
                    blocks: 0 // ✅ Ensure this matches your database column
                };
            });

            [...activePlayers.B, ...benchPlayers.B].forEach(player => {
                const key = `B_${player.id}`;
                playerStats[key] = {
                    player_id: player.id,
                    team_id: player.team_id,
                    points: 0,
                    fouls: player.fouls || 0,
                    free_throws_made: 0,
                    free_throws_attempted: 0,
                    two_points_made: 0,
                    two_points_attempted: 0,
                    three_points_made: 0,
                    three_points_attempted: 0,
                    assists: 0,
                    steals: 0,
                    rebounds: 0,
                    blocks: 0 // ✅ Ensure this matches your database column
                };
            });

            console.log('Initial player stats:', playerStats);
            console.log('Processing game events:', gameEvents);

            // Process game events to calculate stats
            gameEvents.forEach(event => {
                // Skip system/team events
                if (event.player === 'TEAM' || event.player === 'SYSTEM') {
                    return;
                }

                // Find the player
                const player = findPlayerByNumber(event.player, event.team);
                if (!player) {
                    console.warn(`Player not found for event:`, event);
                    return;
                }

                const key = `${event.team}_${player.id}`;
                if (!playerStats[key]) {
                    console.warn(`No stats entry for player ${player.id}`);
                    return;
                }

                // Process scoring events
                if (event.action.includes('Points') || event.action.includes('Made')) {
                    playerStats[key].points += (event.points || 0);

                    // Track shot types
                    if (event.action.includes('Free Throw')) {
                        if (event.action.includes('Made')) {
                            playerStats[key].free_throws_made++;
                            playerStats[key].free_throws_attempted++;
                        } else if (event.action.includes('Miss')) {
                            playerStats[key].free_throws_attempted++;
                        }
                    } else if (event.action.includes('2 Points')) {
                        playerStats[key].two_points_made++;
                        playerStats[key].two_points_attempted++;
                    } else if (event.action.includes('3 Points')) {
                        playerStats[key].three_points_made++;
                        playerStats[key].three_points_attempted++;
                    }
                }

                // ✅ FIXED: Process assists (exact match)
                if (event.action === 'Assist') {
                    playerStats[key].assists++;
                    console.log(`Assist recorded for player ${player.id}`);
                }

                // ✅ FIXED: Process steals (exact match)
                if (event.action === 'Steal') {
                    playerStats[key].steals++;
                    console.log(`Steal recorded for player ${player.id}`);
                }

                // ✅ FIXED: Process rebounds (exact match)
                if (event.action === 'Rebound') {
                    playerStats[key].rebounds++;
                    console.log(`Rebound recorded for player ${player.id}`);
                }

                // ✅ FIXED: Process blocks (match your button's data-action)
                if (event.action === 'blocks') { // Changed from 'block' to 'blocks'
                    playerStats[key].blocks++;
                    console.log(`Block recorded for player ${player.id}`);
                }

                console.log(`Updated stats for player ${player.id}:`, playerStats[key]);
            });

            // Convert to array
            const statsArray = Object.values(playerStats);
            console.log('Final player stats array:', statsArray);
            return statsArray;
        }

        // NEW: Retry save function
        function retryGameSave() {
            saveGameResults();
        }

        // =============== TIMEOUT FUNCTIONALITY =================
        function enterTimeoutMode() {
            timeoutMode = true;
            timeoutInstruction.style.display = 'block';
            teamSectionA.classList.add('timeout-selectable');
            teamSectionB.classList.add('timeout-selectable');
            timeoutBtn.textContent = 'Select Team...';
            timeoutBtn.classList.add('selected');
        }

        function exitTimeoutMode() {
            timeoutMode = false;
            timeoutInstruction.style.display = 'none';
            teamSectionA.classList.remove('timeout-selectable');
            teamSectionB.classList.remove('timeout-selectable');
        }

        function startTimeoutTimer(team) {
            // Auto-pause game timer when timeout starts
            pauseTimer('timeout');

            exitTimeoutMode();

            // Log the timeout event
            logEvent(team, 'TEAM', 'Timeout', 0);

            // Start the 1-minute countdown
            timeoutTime = 60;
            timeoutBtn.classList.add('timer-active');
            updateTimeoutDisplay();

            timeoutTimer = setInterval(() => {
                timeoutTime--;
                updateTimeoutDisplay();

                if (timeoutTime <= 0) {
                    endTimeout();
                }
            }, 1000);
        }

        function updateTimeoutDisplay() {
            const minutes = Math.floor(timeoutTime / 60);
            const seconds = timeoutTime % 60;
            timeoutBtn.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }

        function endTimeout() {
            clearInterval(timeoutTimer);
            timeoutTimer = null;
            timeoutBtn.classList.remove('timer-active');
            timeoutBtn.classList.remove('selected');
            timeoutBtn.textContent = 'Timeout';

            // Show resume prompt
            showResumePrompt('Timeout ended. Resume game clock?');
        }

        // Timeout button click handler
        timeoutBtn.addEventListener('click', () => {
            if (timeoutTimer) {
                // If timer is running, allow early end
                if (confirm('End timeout early?')) {
                    endTimeout();
                }
            } else if (!timeoutMode) {
                // Enter timeout selection mode
                enterTimeoutMode();
            }
        });

        // Team section click handlers for timeout
        teamSectionA.addEventListener('click', () => {
            if (timeoutMode) {
                startTimeoutTimer('A');
            }
        });

        teamSectionB.addEventListener('click', () => {
            if (timeoutMode) {
                startTimeoutTimer('B');
            }
        });

        // Cancel timeout mode if clicking elsewhere
        document.addEventListener('click', (e) => {
            if (timeoutMode && !e.target.closest('.team-section') && !e.target.closest('#timeoutBtn')) {
                exitTimeoutMode();
                timeoutBtn.classList.remove('selected');
                timeoutBtn.textContent = 'Timeout';
            }
        });

        // =============== EVENT LOGGING =================
        function logEvent(team, player, action, points = 0) {
            const event = {
                id: eventCounter,
                team,
                player,
                action,
                points,
                time: timerDisplay.textContent,
                period: `Q${currentQuarter}`
            };

            gameEvents.unshift(event);
            eventCounter++;

            updateScores(team, action, points);
            renderLog();

        }

        function updateScores(team, action, points) {
            if (action.includes("Points") || action === "Free Throw" || action.includes("Made")) {
                if (team === "A") {
                    scoreA += points;
                    scoreADisplay.textContent = scoreA.toString().padStart(2, '0');
                    resetShotClock(24);
                    if (isRunning) startShotClock();
                } else {
                    scoreB += points;
                    scoreBDisplay.textContent = scoreB.toString().padStart(2, '0');
                    resetShotClock(24);
                    if (isRunning) startShotClock();
                }
            }

            // Enhanced foul handling with penalty system
            if (action.includes("Foul")) {
                if (team === "A") {
                    foulsA++;
                    foulsADisplay.textContent = foulsA;
                } else {
                    foulsB++;
                    foulsBDisplay.textContent = foulsB;
                }

                // Update penalty status after foul
                updatePenaltyStatus();

                // Add individual player foul
                const currentEvent = gameEvents[0];
                if (currentEvent && currentEvent.player !== 'TEAM') {
                    // ✅ Determine if it's a technical foul
                    const foulType = action === 'Tech Foul' ? 'technical' : 'personal';
                    addPlayerFoul(team, currentEvent.player, foulType);
                }
            }

            if (action.includes("Timeout")) {
                if (team === "A") {
                    timeoutsA++;
                    timeoutsADisplay.textContent = timeoutsA;
                } else {
                    timeoutsB++;
                    timeoutsBDisplay.textContent = timeoutsB;
                }
            }
        }

        // Team penalty tracking
        let teamAPenalty = false;
        let teamBPenalty = false;





        // Function to check and update penalty status
        function updatePenaltyStatus() {
            teamAPenalty = foulsA >= 4;
            teamBPenalty = foulsB >= 4;

            // Update visual indicators
            updatePenaltyIndicators();
        }

        // Update penalty visual indicators
        function updatePenaltyIndicators() {
            const foulsAContainer = document.getElementById("foulsA").parentElement;
            const foulsBContainer = document.getElementById("foulsB").parentElement;

            // Remove existing penalty indicators
            foulsAContainer.classList.remove('penalty-active');
            foulsBContainer.classList.remove('penalty-active');

            // Add penalty indicators
            if (teamAPenalty) {
                foulsAContainer.classList.add('penalty-active');
            }
            if (teamBPenalty) {
                foulsBContainer.classList.add('penalty-active');
            }
        }

        function renderLog() {
            logContent.innerHTML = '';
            gameEvents.forEach(event => {
                const entry = document.createElement('div');
                entry.className = `log-entry team-${event.team.toLowerCase()}`;
                entry.innerHTML = `
          <div class="entry-number">${event.id}</div>
          <div class="entry-team">${event.team}</div>
          <div class="entry-player">${event.player}</div>
          <div class="entry-action">${event.action}</div>
          <div class="entry-period">${event.period}</div>
          <div class="entry-time">${event.time}</div>
          <div class="entry-check">✓</div>
        `;
                logContent.appendChild(entry);
            });
        }



        // =============== SUBSTITUTION FUNCTIONALITY =================
        const substitutionModal = document.getElementById('substitutionModal');
        const subCloseBtn = document.getElementById('subClose');

        // Open substitution modal
        function openSubstitutionModal() {
            // Pause the timer when substitution is opened
            pauseTimer('substitution');
            substitutionModal.style.display = 'flex';
            renderSubstitutionPlayers();
        }

        // Close substitution modal
        function closeSubstitutionModal() {
            substitutionModal.style.display = 'none';

            // ✅ REMOVE PULSE EFFECTS
            document.querySelectorAll('.player-card').forEach(card => {
                card.classList.remove('pulse-effect');
            });

            // Show styled resume modal after closing substitution modal
            showStyledResumeModal('Substitution completed. Resume game clock?');
        }
        // Styled resume modal (like after free throw)
        function showStyledResumeModal(message) {
            // Remove any existing modal
            const oldModal = document.getElementById('styledResumeModal');
            if (oldModal) document.body.removeChild(oldModal);

            const modal = document.createElement('div');
            modal.id = 'styledResumeModal';
            modal.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100vh;
                    background: rgba(0,0,0,0.8);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                `;

            const content = document.createElement('div');
            content.style.cssText = `
                    background: #2d2d2d;
                    border-radius: 12px;
                    padding: 30px;
                    width: 90%;
                    max-width: 600px;
                    text-align: center;
                    border: 3px solid #FF9800;
                `;
            content.innerHTML = `
                    <h3 style="color: #FF9800; margin-bottom: 20px;">RESUME GAME CLOCK</h3>
                    <div style="color: white; margin-bottom: 30px;">${message}</div>
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <button id="styledResumeYes" style="background: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">Resume</button>
                        <button id="styledResumeLater" style="background: #666; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">Keep Paused</button>
                    </div>
                `;
            modal.appendChild(content);
            document.body.appendChild(modal);

            document.getElementById('styledResumeYes').onclick = () => {
                resumeTimer();
                document.body.removeChild(modal);
            };
            document.getElementById('styledResumeLater').onclick = () => {
                pauseReason = 'manual';
                playPauseBtn.style.backgroundColor = '#4CAF50';
                playPauseBtn.title = '';
                document.body.removeChild(modal);
            };
        }

        // Render players in substitution modal
        function renderSubstitutionPlayers() {
            const activePlayersA = document.getElementById('activePlayersA');
            const benchPlayersA = document.getElementById('benchPlayersA');
            const activePlayersB = document.getElementById('activePlayersB');
            const benchPlayersB = document.getElementById('benchPlayersB');

            // Clear existing content
            activePlayersA.innerHTML = '';
            benchPlayersA.innerHTML = '';
            activePlayersB.innerHTML = '';
            benchPlayersB.innerHTML = '';

            // Render Team A active players
            activePlayers.A.forEach(player => {
                const card = createSubPlayerCard(player, 'A', true);
                activePlayersA.appendChild(card);
            });

            // Render Team A bench players
            benchPlayers.A.forEach(player => {
                const card = createSubPlayerCard(player, 'A', false);
                benchPlayersA.appendChild(card);
            });

            // Render Team B active players
            activePlayers.B.forEach(player => {
                const card = createSubPlayerCard(player, 'B', true);
                activePlayersB.appendChild(card);
            });

            // Render Team B bench players
            benchPlayers.B.forEach(player => {
                const card = createSubPlayerCard(player, 'B', false);
                benchPlayersB.appendChild(card);
            });
        }

        // Create substitution player card
        // Create substitution player card
function createSubPlayerCard(player, team, isActive) {
    const card = document.createElement('div');
    
    // Check if player is ejected or fouled out
    const totalFouls = (player.fouls || 0) + (player.techFouls || 0);
    const isEjected = player.ejected || totalFouls >= 5;
    
    card.className = `sub-player-card ${isActive ? 'active-player' : 'bench-player'}`;
    
    // Only make draggable if not ejected/fouled out
    if (!isEjected) {
        card.draggable = true;
    } else {
        card.style.opacity = '0.4';
        card.style.cursor = 'not-allowed';
        card.style.background = '#2a1a1a';
        card.style.border = '2px solid #F44336';
    }
    
    card.dataset.team = team;
    card.dataset.number = player.number || '00';
    card.dataset.isActive = isActive;
    card.dataset.ejected = isEjected ? 'true' : 'false';

    const lastName = ((player.last_name || player.name || '') + '').trim().split(/\s+/).slice(-1)[0] || '';

    let statusText = isActive ? 'Active' : 'Bench';
    let statusClass = isActive ? 'active' : 'bench';
    
    if (isEjected) {
        statusText = totalFouls >= 5 ? 'Fouled Out' : 'Ejected';
        statusClass = 'fouled-out';
    }

    card.innerHTML = `
        <div class="sub-player-number">${player.number || '00'}</div>
        <div class="sub-player-lastname">${lastName}</div>
        <div class="sub-player-position">${player.position || 'P'}</div>
        <div class="sub-player-status ${statusClass}">${statusText}</div>
    `;

    // Only add drag event listeners if not ejected
    if (!isEjected) {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragend', handleDragEnd);
        card.addEventListener('dragover', handleDragOver);
        card.addEventListener('drop', handleDrop);
        card.addEventListener('dragenter', handleDragEnter);
        card.addEventListener('dragleave', handleDragLeave);
    }

    return card;
}

        // Drag and drop event handlers - FIXED VERSION
        let draggedElement = null;

        function handleDragStart(e) {
            draggedElement = e.target;
            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', e.target.outerHTML);
        }

        function handleDragEnd(e) {
            e.target.classList.remove('dragging');
            document.querySelectorAll('.sub-player-card').forEach(card => {
                card.classList.remove('drag-over');
            });
            draggedElement = null;
        }

        function handleDragOver(e) {
            e.preventDefault(); // CRITICAL: This is required to allow dropping
            e.dataTransfer.dropEffect = 'move';
            return false;
        }

        function handleDragEnter(e) {
            e.preventDefault(); // CRITICAL: This is required to allow dropping
           if (draggedElement && draggedElement !== dropTarget && canDropOn(draggedElement, dropTarget)) {
    makeSubstitution(draggedElement, dropTarget);
}
            return false;
        }

        function handleDragLeave(e) {
            // Only remove drag-over if we're actually leaving the element
            if (!e.target.contains(e.relatedTarget)) {
                e.target.classList.remove('drag-over');
            }
        }


// ✅ ADD THIS FUNCTION
function canDropOn(draggedCard, targetCard) {
    if (!draggedCard || !targetCard) return false;
    
    // Get the closest .sub-player-card elements
    const dragged = draggedCard.closest('.sub-player-card');
    const target = targetCard.closest('.sub-player-card');
    
    if (!dragged || !target) return false;
    
    // Can't drop on yourself
    if (dragged === target) return false;
    
    // Must be same team
    if (dragged.dataset.team !== target.dataset.team) return false;
    
    // ✅ Check if either player is ejected or fouled out
    if (dragged.dataset.ejected === 'true' || target.dataset.ejected === 'true') {
        return false;
    }
    
    // Can only swap: bench → active or active → bench
    const draggedIsActive = dragged.dataset.isActive === 'true';
    const targetIsActive = target.dataset.isActive === 'true';
    
    // One must be active, one must be bench
    return draggedIsActive !== targetIsActive;
}

// Check if a player can be dropped on another - ENHANCED VERSION

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

        // Check if a player can be dropped on another - ENHANCED VERSION
        // Auto-substitute fouled out player
function autoSubstituteFouledOutPlayer(fouledOutPlayer, team) {
    // Find available bench players (not fouled out and not ejected)
    const availableBench = benchPlayers[team].filter(p => {
        const totalFouls = (p.fouls || 0) + (p.techFouls || 0);
        return totalFouls < 5 && !p.ejected;
    });

    if (availableBench.length === 0) {
        alert(`⚠️ No available substitutes for Team ${team}!\nAll bench players are either fouled out or ejected.\nGame may need to continue with fewer players.`);
        return;
    }

    // Get the first available bench player
    const substitutePlayer = availableBench[0];

    // Perform the substitution
    const activeIndex = activePlayers[team].findIndex(p => p.id === fouledOutPlayer.id);
    const benchIndex = benchPlayers[team].findIndex(p => p.id === substitutePlayer.id);

    if (activeIndex !== -1 && benchIndex !== -1) {
        // Swap players
        activePlayers[team][activeIndex] = substitutePlayer;
        benchPlayers[team][benchIndex] = fouledOutPlayer;

        // Log the forced substitution
        logEvent(team, `${fouledOutPlayer.number}→${substitutePlayer.number}`, 'Foul Out Substitution', 0);

        // Update displays
        updateMainRoster();
        updateSubstitutionDisplay();

        // Show substitution notification
        showSubstitutionSuccess(team, fouledOutPlayer.number, substitutePlayer.number,
            'Automatic substitution due to foul out');
    }
}

        // Enhanced substitution function with better error handling
        function makeSubstitution(benchCard, activeCard) {
            console.log('Making substitution:', {
                bench: benchCard.dataset.number,
                active: activeCard.dataset.number,
                team: benchCard.dataset.team
            });

            const team = benchCard.dataset.team;
            const benchNumber = benchCard.dataset.number;
            const activeNumber = activeCard.dataset.number;

            // Find players in arrays
            const benchPlayerIndex = benchPlayers[team].findIndex(p => (p.number || '00').toString() === benchNumber);
            const activePlayerIndex = activePlayers[team].findIndex(p => (p.number || '00').toString() === activeNumber);

            console.log('Player indices:', {
                benchPlayerIndex,
                activePlayerIndex
            });

            if (benchPlayerIndex === -1 || activePlayerIndex === -1) {
                console.error('Could not find players in arrays');
                return;
            }

            // Swap players
            const benchPlayer = benchPlayers[team][benchPlayerIndex];
            const activePlayer = activePlayers[team][activePlayerIndex];

            benchPlayers[team][benchPlayerIndex] = activePlayer;
            activePlayers[team][activePlayerIndex] = benchPlayer;

            console.log('Substitution completed:', {
                benchPlayer: benchPlayer.name,
                activePlayer: activePlayer.name
            });

            // Log substitution event
            logEvent(team, `${activeNumber}→${benchNumber}`, 'Substitution', 0);

            // Update displays
            renderSubstitutionPlayers();
            updateMainRoster();

            // Show success feedback
            showSubstitutionSuccess(team, activeNumber, benchNumber);
        }

        // Update main roster display
        function updateMainRoster() {
            const playersGrid = document.getElementById('playersGrid');
            const teamADiv = playersGrid.children[0];
            const teamBDiv = playersGrid.children[1];

            // Clear and rebuild Team A
            teamADiv.innerHTML = '';
            activePlayers.A.forEach(player => {
                const card = createPlayerCard(player, 'A');
                teamADiv.appendChild(card);
            });

            // Clear and rebuild Team B
            teamBDiv.innerHTML = '';
            activePlayers.B.forEach(player => {
                const card = createPlayerCard(player, 'B');
                teamBDiv.appendChild(card);
            });
        }

        function createPlayerCard(player, team) {
            const card = document.createElement('div');
            card.className = `player-card team-${team.toLowerCase()}`;
            card.dataset.team = team;
            card.dataset.number = player.number || '00';
            card.dataset.playerId = player.id;

            // Add warning or foul-out classes
            const totalFouls = (player.fouls || 0) + (player.techFouls || 0);
            if (totalFouls >= 5) {
                card.classList.add('fouled-out');
            } else if (totalFouls >= 4) {
                card.classList.add('warning-fouls');
            }

            const lastName = ((player.last_name || player.name || '') + '').trim().split(/\s+/).slice(-1)[0] || '';

            card.innerHTML = `
        <div class="player-number">${player.number || '00'}</div>
        <div class="player-lastname">${lastName}</div>
        <div class="player-position">${player.position || 'P'}</div>
        ${createFoulIndicator(player.fouls || 0, player.techFouls || 0)}
    `;

            // Only add click listener if player is not fouled out
            if (totalFouls < 5) {
                card.addEventListener('click', handlePlayerClick);
            }

            return card;
        }

        function createFoulIndicator(foulCount, techFoulCount = 0) {
            let dots = '';
            const totalFouls = foulCount + techFoulCount;

            for (let i = 0; i < 5; i++) {
                let dotClass = 'foul-dot';

                if (i < totalFouls) {
                    // Show violet for tech fouls (last dots)
                    if (i >= foulCount && techFoulCount > 0) {
                        dotClass += ' tech';
                    } else if (totalFouls >= 4 && i >= 3) {
                        dotClass += ' warning';
                    } else {
                        dotClass += ' active';
                    }
                }
                dots += `<div class="${dotClass}"></div>`;
            }
            return `<div class="foul-indicator">${dots}</div>`;
        }

        // Show substitution success message
        function showSubstitutionSuccess(team, outNumber, inNumber, reason = null) {
            const message = document.createElement('div');
            message.style.cssText = `
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: ${reason ? '#F44336' : '#4CAF50'};
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: bold;
        z-index: 3000;
        text-align: center;
        max-width: 400px;
        animation: fadeIn 0.3s;
      `;

            message.innerHTML = `
        <div>Team ${team}: Player #${outNumber} → #${inNumber}</div>
        ${reason ? `<div style="font-size: 12px; margin-top: 4px;">${reason}</div>` : ''}
      `;

            document.body.appendChild(message);

            setTimeout(() => {
                document.body.removeChild(message);
            }, reason ? 4000 : 3000); // Longer display for foul-out notifications
        }

        // Event listeners for substitution modal
        subCloseBtn.addEventListener('click', closeSubstitutionModal);
        // Open substitution modal when substitution button is clicked
        document.querySelector('.action-btn.substitution').addEventListener('click', openSubstitutionModal);

        // Close modal when clicking outside
        substitutionModal.addEventListener('click', (e) => {
            if (e.target === substitutionModal) {
                closeSubstitutionModal();
            }
        });

        let selectedAction = null;

        // =============== FOUL MODAL FUNCTIONALITY =================
        // Foul Modal Variables
        let currentFoulData = {
            foulingPlayer: null,
            foulingTeam: '',
            foulType: '',
            freeThrows: 0,
            fouledPlayer: null
        };

        const foulModal = document.getElementById('foulModal');
        const foulClose = document.getElementById('foulClose');

        // Update the action button event listener to handle foul specially
        // Update the action button event listener to handle foul specially and add visual feedback
        document.querySelectorAll(".action-btn:not(#undoBtn):not(#timeoutBtn)").forEach(btn => {
            btn.addEventListener("click", () => {
                // Exit timeout mode if another action is selected
                if (timeoutMode) {
                    exitTimeoutMode();
                    timeoutBtn.classList.remove('selected');
                    timeoutBtn.textContent = 'Timeout';
                }

                // Remove selection from all buttons
                document.querySelectorAll(".action-btn").forEach(b => b.classList.remove("selected"));

                // Add selection to clicked button
                btn.classList.add("selected");
                selectedAction = btn;

                // ✅ ADD PULSE EFFECT TO ALL PLAYER CARDS
                document.querySelectorAll('.player-card').forEach(card => {
                    card.classList.add('pulse-effect');
                });

                // ✅ SHOW TOAST NOTIFICATION
                const actionName = btn.dataset.action;
                showToast(`👆 Select a player who committed: ${actionName}`, 'info', 5000);

                // Handle foul button specially
                // Handle foul button specially
                if (btn.dataset.action === 'Foul') {
                    showFoulModal();
                } else if (btn.dataset.action === 'Tech Foul') {
                    // ✅ NEW: Show tech foul modal instead
                    showTechFoulModal();
                }
            });
        });

        // Show foul modal
        // Show foul modal
function showFoulModal() {
    // ✅ Pause game timer when foul modal opens
    pauseTimer('foul');
    
    // ✅ Pause shot clock when foul modal opens
    pauseShotClock();
    
    resetFoulModal();
    foulModal.style.display = 'flex';
    setupFoulEventListeners();
    renderFoulingPlayers();
}

        // ✅ CLICK ANYWHERE TO CANCEL SELECTED ACTION
        document.addEventListener('click', function(e) {
            // Only proceed if there's a selected action
            if (!selectedAction) return;

            // Check if click is outside player cards and action buttons
            const clickedPlayerCard = e.target.closest('.player-card');
            const clickedActionBtn = e.target.closest('.action-btn');
            const clickedModal = e.target.closest(
                '.foul-modal, .substitution-modal, .free-throw-panel, .quarter-modal');
            const clickedTeamSection = e.target.closest('.team-section'); // For timeout selection

            // If clicked outside relevant areas, cancel the selection
            if (!clickedPlayerCard && !clickedActionBtn && !clickedModal && !clickedTeamSection) {
                cancelActionSelection();
            }
        });

        // Function to cancel action selection
        function cancelActionSelection() {
            if (!selectedAction) return;

            // Remove selection from action button
            selectedAction.classList.remove("selected");
            selectedAction = null;

            // Remove pulse effects from all player cards
            document.querySelectorAll('.player-card').forEach(card => {
                card.classList.remove('pulse-effect');
            });

            // Show cancellation toast
            showToast('❌ Action cancelled', 'info', 2000);

            // Exit timeout mode if active
            if (timeoutMode) {
                exitTimeoutMode();
                timeoutBtn.classList.remove('selected');
                timeoutBtn.textContent = 'Timeout';
            }

            console.log('Action selection cancelled');
        }

        // Hide foul modal
        function hideFoulModal() {
            foulModal.style.display = 'none';
            resetSelectedAction();

            // ✅ REMOVE PULSE EFFECTS
            document.querySelectorAll('.player-card').forEach(card => {
                card.classList.remove('pulse-effect');
            });
        }

        // Reset foul modal to initial state
        function resetFoulModal() {
            document.getElementById('foulingPlayerStep').style.display = 'block';
            document.getElementById('foulTypeStep').style.display = 'none';
            document.getElementById('fouledPlayerStep').style.display = 'none';
            currentFoulData = {
                foulingPlayer: null,
                foulingTeam: '',
                foulType: '',
                freeThrows: 0,
                fouledPlayer: null
            };
        }

        // Reset selected action
        function resetSelectedAction() {
            if (selectedAction) {
                selectedAction.classList.remove("selected");
                selectedAction = null;
            }
        }

        // Setup foul modal event listeners
        function setupFoulEventListeners() {
            // Close button
            foulClose.removeEventListener('click', hideFoulModal);
            foulClose.addEventListener('click', hideFoulModal);

            // Click outside to close
            foulModal.removeEventListener('click', handleModalClick);
            foulModal.addEventListener('click', handleModalClick);
        }

        // Handle modal click (close when clicking outside)
        function handleModalClick(e) {
            if (e.target === foulModal) {
                hideFoulModal();
            }
        }

        // Render fouling players (Step 1)
        function renderFoulingPlayers() {
            document.getElementById('teamAFoulingTitle').textContent = gameData.team1.name;
            document.getElementById('teamBFoulingTitle').textContent = gameData.team2.name;

            const playersAGrid = document.getElementById('foulingPlayersA');
            const playersBGrid = document.getElementById('foulingPlayersB');

            // Clear existing players
            playersAGrid.innerHTML = '';
            playersBGrid.innerHTML = '';

            // Render Team A active players
            activePlayers.A.forEach(player => {
                const card = createFoulingPlayerCard(player, 'A');
                playersAGrid.appendChild(card);
            });

            // Render Team B active players
            activePlayers.B.forEach(player => {
                const card = createFoulingPlayerCard(player, 'B');
                playersBGrid.appendChild(card);
            });
        }

        // Create fouling player card
        function createFoulingPlayerCard(player, team) {
            const card = document.createElement('div');
            card.className = 'foul-player-card';

            card.innerHTML = `
        <div class="foul-player-number">${player.number || '00'}</div>
        <div class="foul-player-position">${player.position || 'P'}</div>
      `;

            card.addEventListener('click', () => handleFoulingPlayerSelection(player, team));

            return card;
        }

        // Handle fouling player selection (Step 1)
        function handleFoulingPlayerSelection(player, team) {
            currentFoulData.foulingPlayer = player;
            currentFoulData.foulingTeam = team;

            console.log('Fouling player selected:', player, 'Team:', team);

            // Show foul type selection
            showFoulTypeSelection();
        }

        // Show foul type selection (Step 2)
        function showFoulTypeSelection() {
            document.getElementById('foulingPlayerStep').style.display = 'none';
            document.getElementById('foulTypeStep').style.display = 'block';

            // Update fouling player info
            const playerInfo =
                `#${currentFoulData.foulingPlayer.number || '00'} ${currentFoulData.foulingPlayer.name || 'Player'}`;
            document.getElementById('foulingPlayerInfo').textContent = playerInfo;

            // Add event listeners to foul type buttons
            document.querySelectorAll('.foul-option-btn').forEach(btn => {
                btn.removeEventListener('click', handleFoulTypeSelection);
                btn.addEventListener('click', handleFoulTypeSelection);
            });
        }

        // Handle foul type selection (Step 2)
        function handleFoulTypeSelection(e) {
            const foulType = e.target.dataset.type;
            const freeThrows = parseInt(e.target.dataset.freeThrows);

            currentFoulData.foulType = foulType;
            currentFoulData.freeThrows = freeThrows;

            console.log('Foul type selected:', foulType, 'Free throws:', freeThrows);

            if (freeThrows === 0) {
                // Personal foul - no free throws, just log and close
                logPersonalFoul();
                hideFoulModal();
            } else {
                // Shooting foul - show fouled player selection
                showFouledPlayerSelection();
            }
        }

        // Log personal foul (no free throws)
        // Log personal foul with penalty check
        // Updated logPersonalFoul function with proper foul tracking
        function logPersonalFoul() {
            const foulingPlayer = currentFoulData.foulingPlayer;
            const playerNumber = foulingPlayer.number || '00';
            const foulingTeam = currentFoulData.foulingTeam;

            // Check if FOULING team is in penalty (not opposing team)
            const opposingTeam = foulingTeam === 'A' ? 'B' : 'A';
            const foulingTeamInPenalty = (foulingTeam === 'A' && teamAPenalty) || (foulingTeam === 'B' && teamBPenalty);

            if (foulingTeamInPenalty) {
                // Penalty situation - award 2 free throws to opposing team
                const foulDescription = 'Personal Foul (Penalty)';

                // Log the foul
                logEvent(foulingTeam, playerNumber, foulDescription, 0);

                // Set up penalty free throws for opposing team
                setupPenaltyFreeThrows(opposingTeam, foulingTeam); // Pass both teams

                console.log(`Penalty foul by player ${playerNumber}, ${opposingTeam} gets 2 free throws`);
            } else {
                // Regular personal foul
                const foulDescription = 'Personal Foul';
                logEvent(foulingTeam, playerNumber, foulDescription, 0);
                console.log('Regular personal foul logged by player:', playerNumber);
            }
        }


        // Setup penalty free throws
        function setupPenaltyFreeThrows(shootingTeam, foulingTeam) {
            // Show penalty free throw selection modal
            showPenaltyFreeThrowModal(shootingTeam, foulingTeam);
        }

        // Updated showPenaltyFreeThrowModal to track fouling team
        function showPenaltyFreeThrowModal(team, foulingTeam) {
            console.log('showPenaltyFreeThrowModal called with team:', team, 'foulingTeam:', foulingTeam);

            const modal = document.createElement('div');
            modal.id = 'penaltyFreeThrowModal';
            modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;

            const content = document.createElement('div');
            content.style.cssText = `
        background: #2d2d2d;
        border-radius: 12px;
        padding: 30px;
        width: 90%;
        max-width: 600px;
        text-align: center;
        border: 3px solid #FF9800;
    `;

            const teamName = team === 'A' ? gameData.team1.name : gameData.team2.name;
            const activePlayersTeam = team === 'A' ? activePlayers.A : activePlayers.B;

            content.innerHTML = `
        <h3 style="color: #FF9800; margin-bottom: 20px;">PENALTY FREE THROWS</h3>
        <div style="color: white; margin-bottom: 30px;">
            ${teamName} gets 2 free throws due to penalty situation.<br>
            Select the shooter:
        </div>
        <div id="penaltyShooterGrid" style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        "></div>
        <button onclick="document.body.removeChild(document.getElementById('penaltyFreeThrowModal'))"
            style="background: #666; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
            Cancel
        </button>
    `;

            modal.appendChild(content);
            document.body.appendChild(modal);

            // Populate shooter options
            const shooterGrid = content.querySelector('#penaltyShooterGrid');
            activePlayersTeam.forEach(player => {
                if (player.fouls < 5) { // Only non-fouled out players
                    const card = document.createElement('div');
                    card.style.cssText = `
                background: #4d4d4d;
                padding: 15px;
                border-radius: 8px;
                cursor: pointer;
                color: white;
                transition: all 0.2s;
                border: 2px solid transparent;
            `;

                    card.innerHTML = `
                <div style="font-size: 16px; font-weight: bold;">#${player.number || '00'}</div>
                <div style="font-size: 12px; color: #aaa; margin-top: 4px;">${player.position || 'P'}</div>
            `;

                    card.addEventListener('click', () => {
                        console.log('Player selected:', player.number);
                        startPenaltyFreeThrows(team, player.number || '00',
                            foulingTeam); // Pass fouling team
                        document.body.removeChild(modal);
                    });

                    card.addEventListener('mouseenter', () => {
                        card.style.background = '#5d5d5d';
                        card.style.borderColor = '#4CAF50';
                    });

                    card.addEventListener('mouseleave', () => {
                        card.style.background = '#4d4d4d';
                        card.style.borderColor = 'transparent';
                    });

                    shooterGrid.appendChild(card);
                }
            });
        }

        // Start penalty free throws
        // Updated startPenaltyFreeThrows to track fouling team
        function startPenaltyFreeThrows(team, playerNumber, foulingTeam) {
            // Set up free throw with selected player
            pendingFreeThrow = {
                team: team,
                number: playerNumber,
                action: 'Penalty Free Throw',
                attempts: [],
                totalAttempts: 2,
                foulingTeam: foulingTeam // Track which team committed the foul
            };

            console.log('Starting penalty free throws:', pendingFreeThrow);
            showFreeThrowPanel();
        }

        // Show fouled player selection (Step 3)
        function showFouledPlayerSelection() {
            document.getElementById('foulTypeStep').style.display = 'none';
            document.getElementById('fouledPlayerStep').style.display = 'block';

            // Update info display
            const foulTypeText = currentFoulData.foulType === 'shooting' ? 'Shooting Foul' : '3-Point Shooting Foul';
            const foulingPlayerText = `#${currentFoulData.foulingPlayer.number || '00'}`;

            document.getElementById('foulTypeInfo').textContent = foulTypeText;
            document.getElementById('foulingPlayerInfo2').textContent = foulingPlayerText;

            // Show opposing team players
            renderFouledPlayers();
        }

        // Render fouled players (opposing team)
        function renderFouledPlayers() {
            const opposingTeam = currentFoulData.foulingTeam === 'A' ? 'B' : 'A';
            const opposingTeamName = opposingTeam === 'A' ? gameData.team1.name : gameData.team2.name;
            const opposingPlayers = opposingTeam === 'A' ? activePlayers.A : activePlayers.B;

            document.getElementById('opposingTeamTitle').textContent = opposingTeamName;

            // Use single column layout for fouled player selection
            const fouledPlayersGrid = document.getElementById('fouledPlayersGrid');
            fouledPlayersGrid.innerHTML = '';

            // Style the parent container for single column
            const foulTeamSection = fouledPlayersGrid.closest('.foul-teams');
            foulTeamSection.style.gridTemplateColumns = '1fr';
            foulTeamSection.style.maxWidth = '500px';
            foulTeamSection.style.margin = '0 auto';

            opposingPlayers.forEach(player => {
                const card = createFouledPlayerCard(player, opposingTeam);
                fouledPlayersGrid.appendChild(card);
            });
        }

        // Hamburger Menu Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerMenu = document.getElementById('hamburgerMenu');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const tallysheetBtn = document.getElementById('tallysheetBtn');

            // ✅ NULL CHECK - prevents the crash
            if (!hamburgerMenu || !dropdownMenu) return;

            // Toggle dropdown menu and button active state
            hamburgerMenu.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
                if (hamburgerBtn) hamburgerBtn.classList.toggle('active');
            });

            // Close dropdown when clicking outside and reset button
            document.addEventListener('click', function(e) {
                if (!hamburgerMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    const hamburgerBtn = document.getElementById('hamburgerBtn');
                    if (hamburgerBtn) hamburgerBtn.classList.remove('active');
                    if (hamburgerBtn) hamburgerBtn.classList.remove('active');
                }
            });

            // Tallysheet functionality (✅ FIXED - only attach if button exists)
            if (tallysheetBtn) {
                tallysheetBtn.addEventListener('click', function() {
                    dropdownMenu.classList.remove('show');
                    const hamburgerBtn = document.getElementById('hamburgerBtn');
                    if (hamburgerBtn) hamburgerBtn.classList.remove('active');
                    handleTallysheet();
                });
            }

            // ❌ REMOVED: postponeBtn - doesn't exist in HTML
            // ❌ REMOVED: handlePostponeGame() function

            function handleTallysheet() {
                const currentGameData = {
                    id: gameData.id,
                    team1_score: scoreA,
                    team2_score: scoreB,
                    team1_fouls: foulsA,
                    team2_fouls: foulsB,
                    team1_timeouts: timeoutsA,
                    team2_timeouts: timeoutsB,
                    current_quarter: currentQuarter,
                    game_time: timerDisplay.textContent,
                    events: gameEvents,
                    period_scores: {
                        team1: periodScores.teamA,
                        team2: periodScores.teamB
                    }
                };

                const tallysheetUrl =
                    `/games/${gameData.id}/basketball-scoresheet?live_data=${encodeURIComponent(JSON.stringify(currentGameData))}`;

                const tallysheetWindow = window.open(
                    tallysheetUrl,
                    'scoresheet',
                    'width=1200,height=900,scrollbars=yes,resizable=yes'
                );

                if (tallysheetWindow) {
                    tallysheetWindow.focus();
                } else {
                    alert('Please allow popups for this site to view the scoresheet.');
                }
            }
        });

        // ✅ ADD THIS AT THE END (before other DOMContentLoaded handlers)
        document.addEventListener('DOMContentLoaded', function() {
            // Add hotkeys button handler
            const hotkeysBtn = document.getElementById('hotkeysBtn');
            if (hotkeysBtn) {
                hotkeysBtn.addEventListener('click', function() {
                    const dropdownMenu = document.getElementById('dropdownMenu');
                    dropdownMenu.classList.remove('show');
                    const hamburgerBtn = document.getElementById('hamburgerBtn');
                    if (hamburgerBtn) hamburgerBtn.classList.remove('active');
                    openHotkeysModal();
                });
            }

            // Add game settings button handler
            const gameSettingsBtn = document.getElementById('gameSettingsBtn');
            if (gameSettingsBtn) {
                gameSettingsBtn.addEventListener('click', function() {
                    const dropdownMenu = document.getElementById('dropdownMenu');
                    dropdownMenu.classList.remove('show');
                    const hamburgerBtn = document.getElementById('hamburgerBtn');
                    if (hamburgerBtn) hamburgerBtn.classList.remove('active');
                    gameSettingsModal.style.display = 'flex';
                });
            }

            // Setup hotkey modal
            const hotkeysClose = document.getElementById('hotkeysClose');
            if (hotkeysClose) {
                hotkeysClose.addEventListener('click', closeHotkeysModal);
            }

            const hotkeysModal = document.getElementById('hotkeysModal');
            if (hotkeysModal) {
                hotkeysModal.addEventListener('click', function(e) {
                    if (e.target === hotkeysModal) {
                        closeHotkeysModal();
                    }
                });
            }

            const resetBtn = document.getElementById('resetHotkeys');
            if (resetBtn) {
                resetBtn.addEventListener('click', resetHotkeysToDefault);
            }

            const saveBtn = document.getElementById('saveHotkeys');
            if (saveBtn) {
                saveBtn.addEventListener('click', saveHotkeysSettings);
            }

            // Setup hotkey inputs
            setupHotkeyInputs();

            // Load saved hotkeys
            loadHotkeys();
        });

        // ✅ GAME SETTINGS HANDLERS
        document.addEventListener('DOMContentLoaded', function() {
            const gameSettingsClose = document.getElementById('gameSettingsClose');
            if (gameSettingsClose) {
                gameSettingsClose.addEventListener('click', function() {
                    document.getElementById('gameSettingsModal').style.display = 'none';
                });
            }

            const gameSettingsModal = document.getElementById('gameSettingsModal');
            if (gameSettingsModal) {
                gameSettingsModal.addEventListener('click', function(e) {
                    if (e.target === gameSettingsModal) {
                        this.style.display = 'none';
                    }
                });
            }
        });

        // Create fouled player card
        function createFouledPlayerCard(player, team) {
            const card = document.createElement('div');
            card.className = 'foul-player-card';

            card.innerHTML = `
        <div class="foul-player-number">${player.number || '00'}</div>
        <div class="foul-player-position">${player.position || 'P'}</div>
      `;

            card.addEventListener('click', () => handleFouledPlayerSelection(player, team));

            return card;
        }

        // Handle fouled player selection (Step 3)
        function handleFouledPlayerSelection(player, team) {
            currentFoulData.fouledPlayer = player;

            console.log('Fouled player selected:', player);

            // Log the shooting foul
            logShootingFoul();

            // Close modal
            hideFoulModal();

            // Start free throw sequence
            startFreeThrowSequence();
        }

        // Handle technical foul selection
        function handleTechnicalFoulSelection(team, playerNumber) {
            // This function is now replaced by the modal flow
            // But we keep it for backward compatibility
            showTechFoulModal();
        }

        // =============== TECHNICAL FOUL MODAL SYSTEM =================
let currentTechFoulData = {
    type: '',
    typeLabel: '',
    team: '',
    playerNumber: '',
    playerName: '',
    consequence: '', // 'free-throws' or 'ejection'
    substituteNumber: ''
};

const techFoulModal = document.getElementById('techFoulModal');
const techFoulClose = document.getElementById('techFoulClose');

// Show technical foul modal
function showTechFoulModal() {
    resetTechFoulModal();
    techFoulModal.style.display = 'flex';
    showTechStep('techTypeStep');
    setupTechFoulListeners();
}

// Hide technical foul modal
function hideTechFoulModal() {
    techFoulModal.style.display = 'none';
    resetTechFoulModal();
    
    // Remove pulse effects
    document.querySelectorAll('.player-card').forEach(card => {
        card.classList.remove('pulse-effect');
    });
    
    // Reset selected action
    if (selectedAction) {
        selectedAction.classList.remove('selected');
        selectedAction = null;
    }
}

// Reset technical foul modal
function resetTechFoulModal() {
    currentTechFoulData = {
        type: '',
        typeLabel: '',
        team: '',
        playerNumber: '',
        playerName: '',
        consequence: '',
        substituteNumber: ''
    };
    
    // Hide all steps
    document.querySelectorAll('.tech-foul-step').forEach(step => {
        step.classList.remove('active');
    });
}

// Show specific tech foul step
function showTechStep(stepId) {
    document.querySelectorAll('.tech-foul-step').forEach(step => {
        step.classList.remove('active');
    });
    document.getElementById(stepId).classList.add('active');
}

// Setup tech foul event listeners
function setupTechFoulListeners() {
    // Close button
    techFoulClose.onclick = hideTechFoulModal;
    
    // Click outside to close
    techFoulModal.onclick = (e) => {
        if (e.target === techFoulModal) {
            hideTechFoulModal();
        }
    };
    
    // Type selection
    document.querySelectorAll('.tech-type-card').forEach(card => {
        card.onclick = () => handleTechTypeSelection(card);
    });
}

// Handle tech type selection
function handleTechTypeSelection(card) {
    const type = card.dataset.type;
    const typeLabels = {
        'unsportsmanlike': 'Unsportsmanlike Behavior',
        'bench': 'Bench/Coach Violations',
        'administrative': 'Administrative Technical',
        'other': 'Other Technical Foul'
    };
    
    currentTechFoulData.type = type;
    currentTechFoulData.typeLabel = typeLabels[type];
    
    // Update display
    document.getElementById('selectedTechType').textContent = typeLabels[type];
    
    // Render player selection
    renderTechPlayerSelection();
    
    // Show player selection step
    showTechStep('techPlayerStep');
}

// Render player selection for tech foul
function renderTechPlayerSelection() {
    const playersAGrid = document.getElementById('techPlayersA');
    const playersBGrid = document.getElementById('techPlayersB');
    
    // Update team titles
    document.getElementById('techTeamATitle').textContent = gameData.team1.name;
    document.getElementById('techTeamBTitle').textContent = gameData.team2.name;
    
    // Clear existing
    playersAGrid.innerHTML = '';
    playersBGrid.innerHTML = '';
    
    // Render Team A players (active + bench)
    [...activePlayers.A, ...benchPlayers.A].forEach(player => {
        const card = createTechPlayerCard(player, 'A');
        playersAGrid.appendChild(card);
    });
    
    // Render Team B players (active + bench)
    [...activePlayers.B, ...benchPlayers.B].forEach(player => {
        const card = createTechPlayerCard(player, 'B');
        playersBGrid.appendChild(card);
    });
}

// Create tech player card
function createTechPlayerCard(player, team) {
    const card = document.createElement('div');
    card.className = 'tech-player-card';
    
    const lastName = ((player.last_name || player.name || '') + '').trim().split(/\s+/).slice(-1)[0] || '';
    
    card.innerHTML = `
        <div class="tech-player-number">${player.number || '00'}</div>
        <div class="tech-player-name">${lastName}</div>
        <div class="tech-player-position">${player.position || 'P'}</div>
    `;
    
    card.onclick = () => handleTechPlayerSelection(player, team);
    
    return card;
}

// Handle tech player selection
function handleTechPlayerSelection(player, team) {
    currentTechFoulData.team = team;
    currentTechFoulData.playerNumber = player.number || '00';
    currentTechFoulData.playerName = player.name || `Player ${player.number}`;
    
    // Update display
    document.getElementById('selectedTechPlayer').textContent = 
        `#${currentTechFoulData.playerNumber} ${currentTechFoulData.playerName} (Team ${team})`;
    
    // Setup consequence listeners
    document.getElementById('techFreeThrowsOption').onclick = () => handleTechConsequence('free-throws');
    document.getElementById('techEjectionOption').onclick = () => handleTechConsequence('ejection');
    
    // Show consequence selection
    showTechStep('techConsequenceStep');
}

// Handle tech consequence selection
function handleTechConsequence(consequence) {
    currentTechFoulData.consequence = consequence;
    
    if (consequence === 'free-throws') {
        // Just free throws, no ejection
        finalizeTechFoul();
    } else if (consequence === 'ejection') {
        // Ejection - need to select substitute
        showEjectionSubstitution();
    }
}

// Show ejection substitution
function showEjectionSubstitution() {
    const team = currentTechFoulData.team;
    const ejectedPlayer = findPlayerByNumber(currentTechFoulData.playerNumber, team);
    
    // Update ejected player info
    document.getElementById('ejectedPlayerInfo').textContent = 
        `Player #${currentTechFoulData.playerNumber} ${currentTechFoulData.playerName}`;
    
    // Get available bench players (not fouled out and not ejected)
    const availableBench = benchPlayers[team].filter(p => {
        const totalFouls = (p.fouls || 0) + (p.techFouls || 0);
        return totalFouls < 5 && !p.ejected;
    });
    
    const substituteGrid = document.getElementById('techSubstitutePlayers');
    substituteGrid.innerHTML = '';
    
    if (availableBench.length === 0) {
        substituteGrid.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; color: #F44336; padding: 20px;">
                ⚠️ No available substitute players! Team must continue with fewer players.
            </div>
        `;
        
        // Show button to continue without substitute
        const continueBtn = document.createElement('button');
        continueBtn.textContent = 'Continue Without Substitute';
        continueBtn.style.cssText = `
            padding: 12px 24px;
            background: #F44336;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            font-weight: bold;
        `;
        continueBtn.onclick = () => finalizeTechFoul();
        substituteGrid.appendChild(continueBtn);
    } else {
        availableBench.forEach(player => {
            const card = createTechPlayerCard(player, team);
            card.onclick = () => {
                currentTechFoulData.substituteNumber = player.number || '00';
                finalizeTechFoul();
            };
            substituteGrid.appendChild(card);
        });
    }
    
    showTechStep('techEjectionSubStep');
}

// Finalize technical foul
function finalizeTechFoul() {
    const { type, typeLabel, team, playerNumber, playerName, consequence, substituteNumber } = currentTechFoulData;
    
    // Log the technical foul
    const techDescription = `Tech Foul (${typeLabel})`;
    logEvent(team, playerNumber, techDescription, 0);
    
    // Add technical foul to player
    addPlayerFoul(team, playerNumber, 'technical');
    
    // Handle ejection if needed
    if (consequence === 'ejection') {
        handleTechEjection(team, playerNumber, substituteNumber);
    }
    
    // Award 2 free throws to opposing team
    const opposingTeam = team === 'A' ? 'B' : 'A';
    showTechFreeThrowSelection(opposingTeam, typeLabel);
    
    // Close modal
    hideTechFoulModal();
}

// Handle technical foul ejection
function handleTechEjection(team, playerNumber, substituteNumber) {
    const ejectedPlayer = findPlayerByNumber(playerNumber, team);
    
    if (!ejectedPlayer) return;
    
    // Mark player as ejected
    ejectedPlayer.ejected = true;
    
    // Log ejection
    logEvent(team, playerNumber, 'Player Ejected (Technical Foul)', 0);
    
    // If player is active and we have a substitute
    if (substituteNumber && activePlayers[team].some(p => (p.number || '00').toString() === playerNumber.toString())) {
        const substitute = findPlayerByNumber(substituteNumber, team);
        
        if (substitute) {
            // Perform substitution
            const activeIndex = activePlayers[team].findIndex(p => (p.number || '00').toString() === playerNumber.toString());
            const benchIndex = benchPlayers[team].findIndex(p => (p.number || '00').toString() === substituteNumber.toString());
            
            if (activeIndex !== -1 && benchIndex !== -1) {
                activePlayers[team][activeIndex] = substitute;
                benchPlayers[team][benchIndex] = ejectedPlayer;
                
                // Log substitution
                logEvent(team, `${playerNumber}→${substituteNumber}`, 'Forced Substitution (Ejection)', 0);
                
                updateMainRoster();
                updateSubstitutionDisplay();
                
                showSubstitutionSuccess(team, playerNumber, substituteNumber, 'Forced substitution due to ejection');
            }
        }
    }
    
    // Show ejection notification
    showEjectionNotification(ejectedPlayer, team);
}

// Show ejection notification
function showEjectionNotification(player, team) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
        color: white;
        padding: 30px 40px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(244, 67, 54, 0.6);
        z-index: 9500;
        text-align: center;
        font-weight: bold;
        animation: ejectionPulse 0.5s ease-out;
        border: 3px solid white;
    `;
    
    notification.innerHTML = `
        <div style="font-size: 48px; margin-bottom: 15px;">🚫</div>
        <div style="font-size: 24px; margin-bottom: 10px;">PLAYER EJECTED</div>
        <div style="font-size: 18px;">Player #${player.number || '00'} (Team ${team})</div>
        <div style="font-size: 14px; margin-top: 10px; opacity: 0.9;">
            Ejected due to Technical Foul
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        document.body.removeChild(notification);
    }, 4000);
}

// Show tech free throw selection
function showTechFreeThrowSelection(team, techType) {
    const modal = document.createElement('div');
    modal.id = 'techFreeThrowModal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;
    
    const content = document.createElement('div');
    content.style.cssText = `
        background: #2d2d2d;
        border-radius: 16px;
        padding: 35px;
        width: 90%;
        max-width: 700px;
        text-align: center;
        border: 3px solid #9C27B0;
    `;
    
    const teamName = team === 'A' ? gameData.team1.name : gameData.team2.name;
    const activePlayersTeam = team === 'A' ? activePlayers.A : activePlayers.B;
    
    content.innerHTML = `
        <h3 style="color: #9C27B0; margin-bottom: 15px; font-size: 24px;">⚠️ TECHNICAL FOUL FREE THROWS</h3>
        <div style="color: white; margin-bottom: 25px; font-size: 16px;">
            ${teamName} gets 2 free throws<br>
            <small style="color: #CE93D8;">${techType}</small>
        </div>
        <div style="color: #aaa; margin-bottom: 20px; font-size: 14px;">Select shooter:</div>
        <div id="techShooterGrid" style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        "></div>
        <button onclick="document.body.removeChild(document.getElementById('techFreeThrowModal'))"
            style="background: #666; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px;">
            Cancel
        </button>
    `;
    
    modal.appendChild(content);
    document.body.appendChild(modal);
    
    // Populate shooter options
    const shooterGrid = content.querySelector('#techShooterGrid');
    activePlayersTeam.forEach(player => {
        const totalFouls = (player.fouls || 0) + (player.techFouls || 0);
        if (totalFouls < 5 && !player.ejected) {
            const card = document.createElement('div');
            card.style.cssText = `
                background: #4d4d4d;
                padding: 18px 12px;
                border-radius: 10px;
                cursor: pointer;
                color: white;
                transition: all 0.2s;
                border: 3px solid transparent;
            `;
            
            const lastName = ((player.last_name || player.name || '') + '').trim().split(/\s+/).slice(-1)[0] || '';
            
            card.innerHTML = `
                <div style="font-size: 20px; font-weight: bold; margin-bottom: 5px;">#${player.number || '00'}</div>
                <div style="font-size: 12px; color: #ddd;">${lastName}</div>
                <div style="font-size: 11px; color: #aaa; margin-top: 4px;">${player.position || 'P'}</div>
            `;
            
            card.addEventListener('click', () => {
                startTechFreeThrows(team, player.number || '00', techType);
                document.body.removeChild(modal);
            });
            
            card.addEventListener('mouseenter', () => {
                card.style.background = '#5d5d5d';
                card.style.borderColor = '#9C27B0';
                card.style.transform = 'translateY(-3px)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.background = '#4d4d4d';
                card.style.borderColor = 'transparent';
                card.style.transform = 'translateY(0)';
            });
            
            shooterGrid.appendChild(card);
        }
    });
}

// Start technical foul free throws
function startTechFreeThrows(team, playerNumber, techType) {
    pendingFreeThrow = {
        team: team,
        number: playerNumber,
        action: `Tech Free Throw (${techType})`,
        attempts: [],
        totalAttempts: 2
    };
    
    console.log('Starting technical free throws:', pendingFreeThrow);
    showFreeThrowPanel();
}

        // Log shooting foul
        function logShootingFoul() {
            const foulingPlayer = currentFoulData.foulingPlayer;
            const playerNumber = foulingPlayer.number || '00';
            const foulDescription = currentFoulData.foulType === 'shooting' ? 'Shooting Foul' : '3-Point Shooting Foul';

            // Log foul by the fouling player
            logEvent(currentFoulData.foulingTeam, playerNumber, foulDescription, 0);

            console.log('Shooting foul logged by player:', playerNumber);
        }

        // Start free throw sequence
        function startFreeThrowSequence() {
            const fouledPlayer = currentFoulData.fouledPlayer;
            const fouledTeam = activePlayers.A.includes(fouledPlayer) ? 'A' : 'B';
            const playerNumber = fouledPlayer.number || '00';

            // Set up free throw with the fouled player as shooter
            pendingFreeThrow = {
                team: fouledTeam,
                number: playerNumber,
                action: 'Free Throw',
                attempts: [],
                totalAttempts: currentFoulData.freeThrows
            };

            console.log('Starting free throw sequence:', pendingFreeThrow);

            showFreeThrowPanel();
        }

        let pendingFreeThrow = null;

        // Handle player clicks for actions - UPDATED
        // Handle player clicks for actions - UPDATED with visual feedback
        function handlePlayerClick(e) {
            if (!selectedAction) {
                showToast("⚠️ Select an action first!", 'error', 2000);
                return;
            }

            const team = e.currentTarget.dataset.team;
            const number = e.currentTarget.dataset.number;
            const action = selectedAction.dataset.action;
            const points = parseInt(selectedAction.dataset.points || 0);

            // ✅ REMOVE PULSE EFFECT FROM ALL PLAYER CARDS
            document.querySelectorAll('.player-card').forEach(card => {
                card.classList.remove('pulse-effect');
            });

            // ✅ SHOW SUCCESS TOAST
            const playerName = e.currentTarget.querySelector('.player-lastname').textContent;
            showToast(`✓ ${action} recorded for #${number} ${playerName}`, 'success', 2000);

            // ✅ ADD THIS: Handle tech foul specially
            if (action === 'Tech Foul') {
                handleTechnicalFoulSelection(team, number);
                selectedAction.classList.remove("selected");
                selectedAction = null;
                return;
            }

            if (action === "Free Throw") {
                pendingFreeThrow = {
                    team,
                    number,
                    action,
                    attempts: [],
                    totalAttempts: 3
                };
                showFreeThrowPanel();
            } else {
                logEvent(team, number, action, points);
            }

            // Reset selection
            selectedAction.classList.remove("selected");
            selectedAction = null;
        }

        function addPlayerFoul(team, playerNumber, foulType = "personal") {
            const playerArrays = team === 'A' ? [...activePlayers.A, ...benchPlayers.A] : [...activePlayers.B, ...
                benchPlayers.B
            ];

            const player = playerArrays.find(p => (p.number || '00').toString() === playerNumber.toString());

            if (player) {
                if (!player.fouls) player.fouls = 0;
                if (!player.techFouls) player.techFouls = 0;

                if (foulType === "technical") {
                    player.techFouls++;
                    console.log(`🟣 Player ${playerNumber} got a TECHNICAL foul (${player.techFouls})`);
                } else {
                    player.fouls++;
                    console.log(`🔴 Player ${playerNumber} now has ${player.fouls} fouls`);
                }

                // Check for warnings and foul-outs (count total fouls)
                const totalFouls = player.fouls + player.techFouls;
                if (totalFouls === 4) {
                    showFoulWarning(player, team);
                } else if (totalFouls === 5) {
                    handleFoulOut(player, team);
                }

                // Update visual display
                updateMainRoster();
                updateSubstitutionDisplay();
            }
        }

        // Helper function to find player by number
        function findPlayerByNumber(playerNumber, team) {
            const playerArrays = team === 'A' ? [...activePlayers.A, ...benchPlayers.A] : [...activePlayers.B, ...
                benchPlayers.B
            ];
            return playerArrays.find(p => (p.number || '00').toString() === playerNumber.toString());
        }


        // Show 4th foul warning
        function showFoulWarning(player, team) {
            const warningDiv = document.createElement('div');
            warningDiv.style.cssText = `
                position: fixed;
                top: 20%;
                left: 50%;
                transform: translateX(-50%);
                background: #FF9800;
                color: white;
                padding: 15px 25px;
                border-radius: 10px;
                font-weight: bold;
                z-index: 5500;
                text-align: center;
                box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4);
                animation: slideDown 0.4s ease-out;
            `;

            warningDiv.innerHTML = `
                <div style="font-size: 16px;">⚠️ FOUL WARNING</div>
                <div style="font-size: 14px; margin-top: 5px;">
                Player #${player.number} (Team ${team}) has 4 fouls!<br>
                <small>Next foul will result in ejection</small>
                </div>
            `;

            document.body.appendChild(warningDiv);

            // Add slide down animation
            const style = document.createElement('style');
            style.textContent = `
                    @keyframes slideDown {
                    0% { transform: translateX(-50%) translateY(-20px); opacity: 0; }
                    100% { transform: translateX(-50%) translateY(0); opacity: 1; }
                    }
                `;
            document.head.appendChild(style);

            setTimeout(() => {
                document.body.removeChild(warningDiv);
                document.head.removeChild(style);
            }, 4000);
        }

        // Handle foul out (5th foul)
        function handleFoulOut(player, team) {
            // Show foul out notification
            showFoulOutNotification(player, team);

            // Auto-substitute if player is active
            if (activePlayers[team].includes(player)) {
                setTimeout(() => {
                    autoSubstituteFouledOutPlayer(player, team);
                }, 2000); // Wait 2 seconds after notification
            }
        }

        // Show foul out notification
        function showFoulOutNotification(player, team) {
            const notificationDiv = document.createElement('div');
            notificationDiv.className = 'foul-out-notification';
            notificationDiv.innerHTML = `
    <div style="font-size: 20px; margin-bottom: 10px;">🚫 FOULED OUT</div>
    <div>Player #${player.number} (Team ${team})</div>
    <div style="font-size: 14px; margin-top: 8px; opacity: 0.9;">
      Ejected with 5 personal fouls
    </div>
  `;

            document.body.appendChild(notificationDiv);

            setTimeout(() => {
                document.body.removeChild(notificationDiv);
            }, 3000);
        }

        // Auto-substitute fouled out player
        function autoSubstituteFouledOutPlayer(fouledOutPlayer, team) {
            // Find available bench players (not fouled out)
            const availableBench = benchPlayers[team].filter(p => p.fouls < 5);

            if (availableBench.length === 0) {
                alert(`No available substitutes for Team ${team}! Game may need to continue with fewer players.`);
                return;
            }

            // Get the first available bench player
            const substitutePlayer = availableBench[0];

            // Perform the substitution
            const activeIndex = activePlayers[team].findIndex(p => p.id === fouledOutPlayer.id);
            const benchIndex = benchPlayers[team].findIndex(p => p.id === substitutePlayer.id);

            if (activeIndex !== -1 && benchIndex !== -1) {
                // Swap players
                activePlayers[team][activeIndex] = substitutePlayer;
                benchPlayers[team][benchIndex] = fouledOutPlayer;

                // Log the forced substitution
                logEvent(team, `${fouledOutPlayer.number}→${substitutePlayer.number}`, 'Foul Out Substitution', 0);

                // Update displays
                updateMainRoster();
                updateSubstitutionDisplay();

                // Show substitution notification
                showSubstitutionSuccess(team, fouledOutPlayer.number, substitutePlayer.number,
                    'Automatic substitution due to foul out');
            }
        }

        // Update substitution display function
        function updateSubstitutionDisplay() {
            if (substitutionModal.style.display === 'flex') {
                renderSubstitutionPlayers();
            }
        }

        // =============== BALL POSSESSION FUNCTIONALITY =================

        function initializePossessionArrows() {
            const possessionLeft = document.getElementById('possessionLeft');
            const possessionRight = document.getElementById('possessionRight');

            // Set initial possession to Team A
            updatePossessionDisplay('A');

            // Add click handlers
            possessionLeft.addEventListener('click', () => togglePossession('A'));
            possessionRight.addEventListener('click', () => togglePossession('B'));
        }

        function togglePossession(team) {
            if (currentPossession !== team) {
                const previousTeam = currentPossession;
                currentPossession = team;
                updatePossessionDisplay(team);

                // Log possession change
                const teamName = team === 'A' ? gameData.team1.name : gameData.team2.name;
                logEvent('GAME', 'SYSTEM', `Possession → ${teamName}`, 0);
                // Reset shot clock on possession change
                resetShotClock(24);
                if (isRunning) {
                    // restart shot clock if game clock is running
                    startShotClock();
                }
            }
        }

        function updatePossessionDisplay(team) {
            const possessionLeft = document.getElementById('possessionLeft');
            const possessionRight = document.getElementById('possessionRight');

            if (team === 'A') {
                possessionLeft.classList.add('active');
                possessionRight.classList.remove('active');
            } else {
                possessionLeft.classList.remove('active');
                possessionRight.classList.add('active');
            }
        }

        // =============== FREE THROW PANEL ================= 
        function showFreeThrowPanel() {
            // Auto-pause timer when free throws start
            pauseTimer('freethrow');

            const panel = document.getElementById("freeThrowPanel");
            const attemptsDiv = document.getElementById("ftAttempts");

            // Update panel title to show timer is paused
            const title = panel.querySelector('h3');
            if (title) {
                const attempts = pendingFreeThrow ? pendingFreeThrow.totalAttempts || 3 : 3;
                title.innerHTML =
                    `Free Throw Attempts (${attempts} attempts)<br><small style="color: #FF9800;">⏸ Timer Paused</small>`;
            }

            attemptsDiv.innerHTML = "";

            // Create buttons based on total attempts
            const totalAttempts = pendingFreeThrow ? pendingFreeThrow.totalAttempts || 3 : 3;

            for (let i = 0; i < totalAttempts; i++) {
                const btnMake = document.createElement("button");
                btnMake.textContent = `Make ${i+1}`;
                btnMake.onclick = () => {
                    if (!pendingFreeThrow.attempts) pendingFreeThrow.attempts = [];
                    pendingFreeThrow.attempts[i] = 1;
                    btnMake.className = "made";
                };

                const btnMiss = document.createElement("button");
                btnMiss.textContent = `Miss ${i+1}`;
                btnMiss.onclick = () => {
                    if (!pendingFreeThrow.attempts) pendingFreeThrow.attempts = [];
                    pendingFreeThrow.attempts[i] = 0;
                    btnMiss.className = "miss";
                };

                const wrapper = document.createElement("div");
                wrapper.style.display = "flex";
                wrapper.style.flexDirection = "column";
                wrapper.style.alignItems = "center";
                wrapper.style.gap = "5px";

                const label = document.createElement("div");
                label.textContent = `FT ${i+1}`;
                label.style.fontSize = "12px";
                label.style.color = "#aaa";

                wrapper.appendChild(label);
                wrapper.appendChild(btnMake);
                wrapper.appendChild(btnMiss);
                attemptsDiv.appendChild(wrapper);
            }

            panel.style.display = "flex";
        }

        // Updated free throw accept handler with correct team foul reset logic
        document.getElementById("ftAccept").addEventListener("click", () => {
            if (!pendingFreeThrow) return;

            const {
                team,
                number,
                action,
                attempts,
                totalAttempts,
                foulingTeam
            } = pendingFreeThrow;

            // Log each free throw attempt
            if (attempts) {
                attempts.forEach((res, i) => {
                    if (res !== undefined && i < (totalAttempts || 3)) {
                        const label = res === 1 ? `${action} (Made ${i+1})` : `${action} (Miss ${i+1})`;
                        const pts = res === 1 ? 1 : 0;
                        logEvent(team, number, label, pts);
                    }
                });
            }

            // Reset team fouls for the team that COMMITTED the foul (was in penalty)
            if (pendingFreeThrow.action === 'Penalty Free Throw' && foulingTeam) {
                if (foulingTeam === 'A') {
                    foulsA = 0;
                    foulsADisplay.textContent = foulsA;
                    console.log('Reset Team A fouls to 0 after penalty free throws');
                } else if (foulingTeam === 'B') {
                    foulsB = 0;
                    foulsBDisplay.textContent = foulsB;
                    console.log('Reset Team B fouls to 0 after penalty free throws');
                }
                updatePenaltyStatus();

                // Log the team foul reset
                logEvent('GAME', 'SYSTEM', `Team ${foulingTeam} fouls reset after penalty`, 0);
            }

            pendingFreeThrow = null;
            document.getElementById("freeThrowPanel").style.display = "none";

            // Show resume prompt
            showResumePrompt('Free throws completed. Resume game clock?');
        });

        // =============== UNDO =================
        document.getElementById('undoBtn').addEventListener('click', () => {
            if (gameEvents.length === 0) return;

            const lastEvent = gameEvents.shift();

            if (lastEvent.action.includes("Points") || lastEvent.action === "Free Throw" || lastEvent.action
                .includes("Made")) {
                if (lastEvent.team === "A") {
                    scoreA -= lastEvent.points;
                    scoreADisplay.textContent = scoreA.toString().padStart(2, '0');
                } else {
                    scoreB -= lastEvent.points;
                    scoreBDisplay.textContent = scoreB.toString().padStart(2, '0');
                }
            }

            if (lastEvent.action.includes("Foul")) {
                if (lastEvent.team === "A") {
                    foulsA = Math.max(0, foulsA - 1);
                    foulsADisplay.textContent = foulsA;
                } else {
                    foulsB = Math.max(0, foulsB - 1);
                    foulsBDisplay.textContent = foulsB;
                }
            }

            if (lastEvent.action.includes("Timeout")) {
                if (lastEvent.team === "A") {
                    timeoutsA = Math.max(0, timeoutsA - 1);
                    timeoutsADisplay.textContent = timeoutsA;
                } else {
                    timeoutsB = Math.max(0, timeoutsB - 1);
                    timeoutsBDisplay.textContent = timeoutsB;
                }
            }

            renderLog();

        });

        // Hotkeys functionality
        let hotkeys = {
            'Free Throw': 'F',
            '2 Points': '2',
            '3 Points': '3',
            'Assist': 'A',
            'Steal': 'S',
            'Rebound': 'R',
            'Foul': 'L',
            'Tech Foul': 'T',
            'Timeout': 'O',
            'Substitution': 'U',
            'Undo': 'Z',
            'PlayPause': ' ' // Space bar
        };

        const defaultHotkeys = {
            ...hotkeys
        };

        // Load saved hotkeys from localStorage
        function loadHotkeys() {
            const saved = localStorage.getItem('gameHotkeys');
            if (saved) {
                hotkeys = JSON.parse(saved);
            }
            applyHotkeys();
        }

        // Save hotkeys to localStorage
        function saveHotkeys() {
            localStorage.setItem('gameHotkeys', JSON.stringify(hotkeys));
        }

        // Apply hotkeys (global keyboard listener)
        function applyHotkeys() {
            document.addEventListener('keydown', handleHotkeyPress);
        }

        // Handle hotkey press
        function handleHotkeyPress(e) {
            // Ignore if typing in input fields or modal is open
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            if (document.querySelector('.hotkeys-modal').style.display === 'flex') return;
            if (document.querySelector('.foul-modal')?.style.display === 'flex') return;
            if (document.querySelector('.substitution-modal')?.style.display === 'flex') return;

            const key = e.key.toUpperCase();

            // Find action for this key
            for (const [action, hotkey] of Object.entries(hotkeys)) {
                if (hotkey.toUpperCase() === key || (hotkey === ' ' && e.code === 'Space')) {
                    e.preventDefault();
                    executeHotkeyAction(action);
                    break;
                }
            }
        }

        // Execute action based on hotkey
        function executeHotkeyAction(action) {
            switch (action) {
                case 'Free Throw':
                    document.querySelector('[data-action="Free Throw"]').click();
                    break;
                case '2 Points':
                    document.querySelector('[data-action="2 Points"]').click();
                    break;
                case '3 Points':
                    document.querySelector('[data-action="3 Points"]').click();
                    break;
                case 'Assist':
                    document.querySelector('[data-action="Assist"]').click();
                    break;
                case 'Steal':
                    document.querySelector('[data-action="Steal"]').click();
                    break;
                case 'Rebound':
                    document.querySelector('[data-action="Rebound"]').click();
                    break;
                case 'blocks':
                    document.querySelector('[data-action="blocks"]').click();
                    break;
                case 'Foul':
                    document.querySelector('[data-action="Foul"]').click();
                    break;
                case 'Tech Foul':
                    document.querySelector('[data-action="Tech Foul"]').click();
                    break;
                case 'Timeout':
                    document.getElementById('timeoutBtn').click();
                    break;
                case 'Substitution':
                    document.querySelector('[data-action="Substitution"]').click();
                    break;
                case 'Undo':
                    document.getElementById('undoBtn').click();
                    break;
                case 'PlayPause':
                    document.getElementById('playPause').click();
                    break;
            }
        }

        // Open hotkeys modal
        function openHotkeysModal() {
            const modal = document.getElementById('hotkeysModal');
            modal.style.display = 'flex';
            updateHotkeyDisplay();
        }

        // Close hotkeys modal
        function closeHotkeysModal() {
            const modal = document.getElementById('hotkeysModal');
            modal.style.display = 'none';
            // Remove any listening state
            document.querySelectorAll('.hotkey-input').forEach(input => {
                input.classList.remove('listening');
            });
        }

        // Update hotkey display in modal
        function updateHotkeyDisplay() {
            for (const [action, key] of Object.entries(hotkeys)) {
                const actionKey = action.toLowerCase().replace(/\s+/g, '').replace('.', '');
                const display = key === ' ' ? 'SPACE' : key.toUpperCase();
                const element = document.getElementById(`key-${actionKey}`);
                if (element) {
                    element.textContent = display;
                }
            }
        }

        // Setup hotkey input listeners
        function setupHotkeyInputs() {
            const inputs = document.querySelectorAll('.hotkey-input');
            let listeningInput = null;

            inputs.forEach(input => {
                input.addEventListener('click', function() {
                    // Remove listening from all inputs
                    inputs.forEach(i => i.classList.remove('listening'));

                    // Add listening to clicked input
                    this.classList.add('listening');
                    listeningInput = this;

                    const keyDisplay = this.querySelector('.current-key');
                    keyDisplay.textContent = 'Press a key...';
                });
            });

            // Listen for key press when an input is listening
            document.addEventListener('keydown', function(e) {
                if (listeningInput) {
                    e.preventDefault();

                    const action = listeningInput.dataset.action;

                    // ESC to clear
                    if (e.key === 'Escape') {
                        hotkeys[action] = '';
                        listeningInput.querySelector('.current-key').textContent = 'NONE';
                    } else {
                        // Set the new hotkey
                        const newKey = e.key === ' ' ? ' ' : e.key.toUpperCase();
                        hotkeys[action] = newKey;
                        const display = newKey === ' ' ? 'SPACE' : newKey;
                        listeningInput.querySelector('.current-key').textContent = display;
                    }

                    listeningInput.classList.remove('listening');
                    listeningInput = null;
                }
            });
        }

        // Reset hotkeys to defaults
        function resetHotkeysToDefault() {
            if (confirm('Reset all hotkeys to default values?')) {
                hotkeys = {
                    ...defaultHotkeys
                };
                updateHotkeyDisplay();
                saveHotkeys();
                showNotification('Hotkeys reset to defaults', '#4CAF50');
            }
        }

        // Save hotkeys
        function saveHotkeysSettings() {
            saveHotkeys();
            closeHotkeysModal();
            showNotification('Hotkeys saved successfully', '#4CAF50');
        }

        // Show notification
        function showNotification(message, color) {
            const notification = document.createElement('div');
            notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${color};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        font-weight: bold;
        z-index: 9999;
        animation: fadeIn 0.3s;
    `;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                document.body.removeChild(notification);
            }, 3000);
        }

        // Initialize hotkeys on page load
        document.addEventListener('DOMContentLoaded', function() {
            // ... existing DOMContentLoaded code ...

            // Add hotkeys button handler
            const hotkeysBtn = document.getElementById('hotkeysBtn');
            if (hotkeysBtn) {
                hotkeysBtn.addEventListener('click', function() {
                    const dropdownMenu = document.getElementById('dropdownMenu');
                    const hamburgerBtn = document.getElementById('hamburgerBtn');
                    if (dropdownMenu) dropdownMenu.classList.remove('show');
                    if (hamburgerBtn) hamburgerBtn.classList.remove('active');
                    openHotkeysModal();
                });
            }

            // Setup hotkey modal
            const hotkeysClose = document.getElementById('hotkeysClose');
            if (hotkeysClose) {
                hotkeysClose.addEventListener('click', closeHotkeysModal);
            }

            const hotkeysModal = document.getElementById('hotkeysModal');
            if (hotkeysModal) {
                hotkeysModal.addEventListener('click', function(e) {
                    if (e.target === hotkeysModal) {
                        closeHotkeysModal();
                    }
                });
            }

            const resetBtn = document.getElementById('resetHotkeys');
            if (resetBtn) {
                resetBtn.addEventListener('click', resetHotkeysToDefault);
            }

            const saveBtn = document.getElementById('saveHotkeys');
            if (saveBtn) {
                saveBtn.addEventListener('click', saveHotkeysSettings);
            }

            // Setup hotkey inputs
            setupHotkeyInputs();

            // Load saved hotkeys
            loadHotkeys();
        });



        // ================= GAME SETTINGS LOGIC =================
        const gameSettingsBtn = document.getElementById("gameSettingsBtn");
        const gameSettingsModal = document.getElementById("gameSettingsModal");
        const gameSettingsClose = document.getElementById("gameSettingsClose");
        const saveGameSettings = document.getElementById("saveGameSettings");

        // Inputs
        const quarterTimeInput = document.getElementById("quarterTimeInput");
        const timeoutDurationInput = document.getElementById("timeoutDurationInput");
        const timeoutLimitInput = document.getElementById("timeoutLimitInput");
        const subsPerQuarterInput = document.getElementById("subsPerQuarterInput");

        // Default game settings
        let gameSettings = {
            quarterTime: 8, // minutes
            timeoutDuration: 60, // seconds
            timeoutLimit: 2,
            subsPerQuarter: 5,
        };

        // Open modal
        gameSettingsBtn.addEventListener("click", () => {
            gameSettingsModal.style.display = "flex";
            quarterTimeInput.value = gameSettings.quarterTime;
            timeoutDurationInput.value = gameSettings.timeoutDuration;
            timeoutLimitInput.value = gameSettings.timeoutLimit;
            subsPerQuarterInput.value = gameSettings.subsPerQuarter;
        });

        // Close modal
        gameSettingsClose.addEventListener("click", () => {
            gameSettingsModal.style.display = "none";
        });

        // Save settings
        saveGameSettings.addEventListener("click", () => {
            gameSettings.quarterTime = parseInt(quarterTimeInput.value);
            gameSettings.timeoutDuration = parseInt(timeoutDurationInput.value);
            gameSettings.timeoutLimit = parseInt(timeoutLimitInput.value);
            gameSettings.subsPerQuarter = parseInt(subsPerQuarterInput.value);

            // Apply to actual game logic
            quarterLength = gameSettings.quarterTime * 60; // seconds
            timeoutTime = gameSettings.timeoutDuration; // seconds
            maxTimeoutsPerQuarter = gameSettings.timeoutLimit;

            // Reset the timer to reflect new quarter time if not running
            if (!isRunning) {
                time = quarterLength;
                updateTimer();
            }

            // Update timeout limit display
            document.querySelectorAll(".max-timeouts").forEach(el => {
                el.textContent = maxTimeoutsPerQuarter;
            });

            alert("✅ Game settings updated successfully!");
            gameSettingsModal.style.display = "none";
        });



        updateTimer();

        initializePossessionArrows();
        initializePlayerRosters();
        initializePlayerFouls();
        updateMainRoster();
        updatePeriodDisplay(); // Initialize quarter display
        updatePenaltyStatus();
        console.log('Game loaded:', gameData);

        // Load saved game state on page load
function loadSavedGameState() {
    fetch(`/games/${gameData.id}/load-state`)
        .then(response => response.json())
        .then(data => {
            if (data.has_saved_state) {
                console.log('📥 Loading saved game state from:', data.last_saved);
                
                // Restore scores
                scoreA = data.team1_score || 0;
                scoreB = data.team2_score || 0;
                scoreADisplay.textContent = scoreA.toString().padStart(2, '0');
                scoreBDisplay.textContent = scoreB.toString().padStart(2, '0');
                
                // Restore fouls
                foulsA = data.team1_fouls || 0;
                foulsB = data.team2_fouls || 0;
                foulsADisplay.textContent = foulsA;
                foulsBDisplay.textContent = foulsB;
                
                // Restore timeouts
                timeoutsA = data.team1_timeouts || 0;
                timeoutsB = data.team2_timeouts || 0;
                timeoutsADisplay.textContent = timeoutsA;
                timeoutsBDisplay.textContent = timeoutsB;
                
                // Restore quarter and time
                currentQuarter = data.current_quarter || 1;
                time = data.time_remaining || quarterLength;
                shotTime = data.shot_clock || 24;
                
                // Restore events
                if (data.game_events && data.game_events.length > 0) {
                    gameEvents = data.game_events;
                    eventCounter = gameEvents.length + 1;
                    renderLog();
                }
                
                // Restore period scores
                if (data.period_scores) {
                    periodScores.teamA = data.period_scores.team1 || [0, 0, 0, 0];
                    periodScores.teamB = data.period_scores.team2 || [0, 0, 0, 0];
                }
                
                // ✅ FIX: Only restore player fouls/status, NOT the entire roster
                if (data.active_players) {
                    // Update Team A active players - only fouls and status
                    if (data.active_players.teamA && Array.isArray(data.active_players.teamA)) {
                        data.active_players.teamA.forEach(savedPlayer => {
                            const player = activePlayers.A.find(p => p.id === savedPlayer.id);
                            if (player) {
                                player.fouls = savedPlayer.fouls || 0;
                                player.techFouls = savedPlayer.techFouls || 0;
                                player.ejected = savedPlayer.ejected || false;
                            }
                        });
                    }
                    
                    // Update Team B active players - only fouls and status
                    if (data.active_players.teamB && Array.isArray(data.active_players.teamB)) {
                        data.active_players.teamB.forEach(savedPlayer => {
                            const player = activePlayers.B.find(p => p.id === savedPlayer.id);
                            if (player) {
                                player.fouls = savedPlayer.fouls || 0;
                                player.techFouls = savedPlayer.techFouls || 0;
                                player.ejected = savedPlayer.ejected || false;
                            }
                        });
                    }
                }
                
                // ✅ FIX: Restore bench players fouls - only fouls and status
                if (data.bench_players) {
                    if (data.bench_players.teamA && Array.isArray(data.bench_players.teamA)) {
                        data.bench_players.teamA.forEach(savedPlayer => {
                            const player = benchPlayers.A.find(p => p.id === savedPlayer.id);
                            if (player) {
                                player.fouls = savedPlayer.fouls || 0;
                                player.techFouls = savedPlayer.techFouls || 0;
                                player.ejected = savedPlayer.ejected || false;
                            }
                        });
                    }
                    
                    if (data.bench_players.teamB && Array.isArray(data.bench_players.teamB)) {
                        data.bench_players.teamB.forEach(savedPlayer => {
                            const player = benchPlayers.B.find(p => p.id === savedPlayer.id);
                            if (player) {
                                player.fouls = savedPlayer.fouls || 0;
                                player.techFouls = savedPlayer.techFouls || 0;
                                player.ejected = savedPlayer.ejected || false;
                            }
                        });
                    }
                }
                
                // Restore possession
                if (data.possession) {
                    currentPossession = data.possession;
                    updatePossessionDisplay(currentPossession);
                }
                
                // ✅ FIX: Re-render the roster with updated foul data
                updateMainRoster();
                
                // Update all displays
                updateTimer();
                updateShotClockDisplay();
                updatePeriodDisplay();
                updatePenaltyStatus();
                
                console.log('✅ Game state fully restored!');
                showToast('Game restored from last save', 'success', 3000);
            } else {
                console.log('ℹ️ No saved state found - starting fresh');
            }
        })
        .catch(error => {
            console.error('❌ Failed to load saved state:', error);
            // Even if loading fails, make sure roster is displayed
            updateMainRoster();
        });
}

setTimeout(() => {
    loadSavedGameState();
}, 500);

// Auto-save game state every 5 seconds
setInterval(function() {
    if (isRunning || gameEvents.length > 0) {
        saveGameState();
    }
}, 5000); // Save every 5 seconds

// Function to save current game state
function saveGameState() {
    const gameStateData = {
        team1_score: scoreA,
        team2_score: scoreB,
        team1_fouls: foulsA,
        team2_fouls: foulsB,
        team1_timeouts: timeoutsA,
        team2_timeouts: timeoutsB,
        current_quarter: currentQuarter,
        time_remaining: time,
        shot_clock: shotTime,
        game_events: gameEvents,
        period_scores: periodScores,
        active_players: {
            teamA: activePlayers.A,
            teamB: activePlayers.B
        },
        bench_players: {
            teamA: benchPlayers.A,
            teamB: benchPlayers.B
        },
        possession: currentPossession,
        is_running: isRunning
    };

    fetch(`/games/${gameData.id}/auto-save`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(gameStateData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Game state auto-saved at:', data.saved_at);
        }
    })
    .catch(error => {
        console.error('❌ Auto-save failed:', error);
    });
}

// Save on page unload
window.addEventListener('beforeunload', function() {
    saveGameState();
});
        

        // ==================== REAL-TIME SYNC WITH POLLING ====================
        (function() {
            const gameId = {{ $game->id }};
            const userRole = '{{ $userRole ?? 'viewer' }}';
            let lastUpdateTimestamp = 0;
            let syncInProgress = false;

            console.log('🔄 Polling sync initialized for game', gameId, 'as', userRole);

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Show sync indicator
            function showSyncIndicator(message, color = '#4CAF50') {
                const indicator = document.createElement('div');
                indicator.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            background: ${color};
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            z-index: 9999;
            animation: fadeInOut 2s;
        `;
                indicator.textContent = message;
                document.body.appendChild(indicator);

                setTimeout(() => {
                    if (indicator.parentNode) {
                        document.body.removeChild(indicator);
                    }
                }, 2000);
            }

            // Add fadeInOut animation
            const style = document.createElement('style');
            style.textContent = `
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(20px); }
            15% { opacity: 1; transform: translateX(0); }
            85% { opacity: 1; transform: translateX(0); }
            100% { opacity: 0; transform: translateX(20px); }
        }
    `;
            document.head.appendChild(style);

            // Fetch and update game state (for stat-keeper)
            function fetchGameState() {
                if (syncInProgress || userRole === 'scorer') return;

                syncInProgress = true;

                fetch(`/api/game-state/${gameId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Only update if there's new data
                        if (data.last_update > lastUpdateTimestamp) {
                            let hasChanges = false;

                            // Update scores
                            if (data.scoreA !== scoreA) {
                                scoreA = data.scoreA;
                                scoreADisplay.textContent = scoreA.toString().padStart(2, '0');
                                hasChanges = true;
                            }

                            if (data.scoreB !== scoreB) {
                                scoreB = data.scoreB;
                                scoreBDisplay.textContent = scoreB.toString().padStart(2, '0');
                                hasChanges = true;
                            }

                            // Update fouls
                            if (data.foulsA !== foulsA) {
                                foulsA = data.foulsA;
                                foulsADisplay.textContent = foulsA;
                                hasChanges = true;
                            }

                            if (data.foulsB !== foulsB) {
                                foulsB = data.foulsB;
                                foulsBDisplay.textContent = foulsB;
                                hasChanges = true;
                            }

                            // Update timeouts
                            if (data.timeoutsA !== timeoutsA) {
                                timeoutsA = data.timeoutsA;
                                timeoutsADisplay.textContent = timeoutsA;
                                hasChanges = true;
                            }

                            if (data.timeoutsB !== timeoutsB) {
                                timeoutsB = data.timeoutsB;
                                timeoutsBDisplay.textContent = timeoutsB;
                                hasChanges = true;
                            }

                            // Update events log
                            if (data.events && data.events.length > gameEvents.length) {
                                gameEvents = data.events;
                                renderLog();
                                hasChanges = true;
                            }

                            if (hasChanges) {
                                lastUpdateTimestamp = data.last_update;
                                showSyncIndicator('✓ Synced', '#4CAF50');
                                console.log('📥 Game state updated from scorer');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('❌ Failed to fetch game state:', error);
                        showSyncIndicator('⚠ Sync failed', '#F44336');
                    })
                    .finally(() => {
                        syncInProgress = false;
                    });
            }

            // Push game state to server (for scorer)
            function pushGameState() {
                if (userRole !== 'scorer') return;

                fetch(`/api/update-game-state/${gameId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            scoreA: scoreA,
                            scoreB: scoreB,
                            foulsA: foulsA,
                            foulsB: foulsB,
                            timeoutsA: timeoutsA,
                            timeoutsB: timeoutsB,
                            events: gameEvents,
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('📤 Game state pushed to server');
                    })
                    .catch(error => {
                        console.error('❌ Failed to push game state:', error);
                        showSyncIndicator('⚠ Save failed', '#F44336');
                    });
            }

            // Fetch connected users
            function updateConnectedUsers() {
                fetch(`/api/connected-users/${gameId}`)
                    .then(response => response.json())
                    .then(data => {
                        displayConnectedUsers(data.connected_users);
                    })
                    .catch(error => {
                        console.error('Failed to fetch connected users:', error);
                    });
            }

            // Display connected users
            function displayConnectedUsers(users) {
                let indicator = document.getElementById('connected-users-indicator');
                if (!indicator) {
                    indicator = document.createElement('div');
                    indicator.id = 'connected-users-indicator';
                    indicator.style.cssText = `
                position: fixed;
                bottom: 20px;
                left: 20px;
                background: rgba(0,0,0,0.85);
                color: white;
                padding: 12px 15px;
                border-radius: 8px;
                font-size: 12px;
                z-index: 9999;
                border: 1px solid #444;
            `;
                    document.body.appendChild(indicator);
                }

                let html = '<div style="font-weight: bold; margin-bottom: 5px;">👥 Connected:</div>';
                if (users.length === 0) {
                    html += '<div style="color: #888;">No users connected</div>';
                } else {
                    users.forEach(user => {
                        const icon = user.role === 'scorer' ? '📊' : '📈';
                        html +=
                            `<div style="margin: 3px 0;">${icon} ${user.user_name} <span style="color: #4CAF50;">(${user.role})</span></div>`;
                    });
                }
                indicator.innerHTML = html;
            }

            // Start polling based on role
            if (userRole === 'scorer') {
                console.log('📊 Running as SCORER - will push updates every 3 seconds');
                setInterval(pushGameState, 3000);
            } else if (userRole === 'stat_keeper') {
                console.log('📈 Running as STAT-KEEPER - will fetch updates every 2 seconds');
                setInterval(fetchGameState, 2000);
            } else {
                console.log('👁 Running as VIEWER - will fetch updates every 5 seconds');
                setInterval(fetchGameState, 5000);
            }

            // Update connected users every 30 seconds
            updateConnectedUsers();
            setInterval(updateConnectedUsers, 30000);

            // Show role indicator
            const roleIndicator = document.createElement('div');
            roleIndicator.style.cssText = `
        position: fixed;
        top: 80px;
        left: 20px;
        background: ${userRole === 'scorer' ? '#2196F3' : '#FF9800'};
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: bold;
        z-index: 9999;
    `;
            roleIndicator.textContent = userRole === 'scorer' ? '📊 SCORER MODE' : '📈 STAT-KEEPER MODE';
            document.body.appendChild(roleIndicator);

        })();
    </script>
</body>

</html>
