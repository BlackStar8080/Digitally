@extends('layouts.app')

@section('title', $tournament->name . ' - Tournament Details')

@push('styles')
    <style>
        :root {
            --primary-purple: #9d4edd;
            --secondary-purple: #7c3aed;
            --accent-purple: #5f2da8;
            --light-purple: #ffffff;
            --border-color: #dee2e6;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }

        .tournament-page {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-purple);
            color: var(--text-dark);
            min-height: 100vh;
            box-sizing: border-box;
            /* Remove padding so navbar stays full width */
        }

        .tournament-page .main-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 2rem auto;
            max-width: 1400px;
        }

        .tournament-page .page-header {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple), var(--accent-purple));
            color: white;
            padding: 2rem;
        }

        .tournament-page .tournament-title {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .tournament-page .tournament-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }

        .tournament-page .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .tournament-page .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
        }

        .tournament-page .content-section {
            padding: 2rem;
        }

        .tournament-page .info-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .tournament-page .info-card:hover {
            border-color: var(--secondary-blue);
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.1);
        }

        .tournament-page .section-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 1rem 0;
            color: var(--text-dark);
        }

        .tournament-page .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .tournament-page .team-item {
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .tournament-page .team-item:hover {
            border-color: var(--secondary-blue);
            background: white;
        }

        /* IMPROVED MODAL STYLES */
        .tournament-page .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(253, 253, 253, 0.25);
            overflow: hidden;
        }

        .tournament-page .modal-header {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: rgb(255, 255, 255);
            border: none;
            padding: 1.5rem 2rem;
            position: relative;
        }

        .tournament-page .modal-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tournament-page .btn-close {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            opacity: 1;
            transition: all 0.3s ease;
        }

        .tournament-page .btn-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .tournament-page .modal-body {
            padding: 2rem;
        }

        .tournament-page .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #f0f0f0;
            background: #fafafa;
        }

        /* CONFIRMATION MODAL IMPROVEMENTS */
        .confirmation-modal .modal-dialog {
            max-width: 400px;
        }

        .confirmation-modal .modal-body {
            text-align: center;
            padding: 2.5rem 2rem;
        }

        .confirmation-modal .icon-container {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .confirmation-modal .icon-container i {
            font-size: 2rem;
            color: white;
        }

        .confirmation-modal .team-name-highlight {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 1.1rem;
        }

        .confirmation-modal .modal-footer {
            justify-content: center;
            gap: 1rem;
        }

        /* MULTIPLE SELECTION INTERFACE */
        .selection-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .selection-info {
            font-weight: 600;
            color: var(--text-dark);
        }

        .selected-count {
            color: var(--primary-blue);
            font-size: 1.1rem;
        }

        .selection-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .selection-actions .btn {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        /* CHECKBOX STYLING */
        .checkbox-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .team-checkbox {
            display: none;
        }

        .checkbox-label {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            background: white;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkbox-label::after {
            content: '';
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg) scale(0);
            transition: transform 0.2s ease;
        }

        .team-checkbox:checked+.checkbox-label {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .team-checkbox:checked+.checkbox-label::after {
            transform: rotate(45deg) scale(1);
        }

        .checkbox-label:hover {
            border-color: var(--secondary-blue);
            background: rgba(66, 133, 244, 0.05);
        }

        /* TEAM CARD SELECTION STATES */
        .team-selection-card.selectable {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .team-selection-card.selectable:hover {
            border-color: var(--secondary-blue);
            background: rgba(66, 133, 244, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.15);
        }

        .team-selection-card.selected {
            border-color: var(--primary-blue);
            background: rgba(44, 124, 249, 0.08);
            box-shadow: 0 4px 15px rgba(44, 124, 249, 0.2);
        }

        .team-selection-card.selected::before {
            background: var(--primary-blue);
        }

        /* ENHANCED TEAM CARD CONTENT LAYOUT */
        .team-card-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .team-info {
            flex: 1;
        }

        .team-info h6 {
            margin: 0 0 0.25rem 0;
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1.2;
        }

        .team-details {
            font-size: 0.8rem;
            color: var(--text-muted);
            display: flex;
            gap: 1rem;
        }

        .team-details .detail-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-shrink: 0;
        }

        .status-badge.available {
            background: var(--success-color);
            color: white;
        }

        .status-badge.assigned {
            background: var(--primary-blue);
            color: white;
        }

        .status-badge.unavailable {
            background: var(--warning-color);
            color: #212529;
        }

        .status-badge.incompatible {
            background: var(--danger-color);
            color: white;
        }

        /* BRACKET CUSTOMIZER IMPROVEMENTS */
        .bracket-customizer {
            display: none;
            margin-top: 2rem;
            background: #f8f9fa;
            border-radius: 16px;
            padding: 2rem;
            border: 2px solid var(--border-color);
        }

        .customizer-instructions {
            background: rgba(44, 124, 249, 0.08);
            border-left: 4px solid var(--primary-blue);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .customizer-instructions h6 {
            color: var(--primary-blue);
            margin-bottom: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .customizer-instructions ul {
            margin-left: 1rem;
            margin-bottom: 0;
        }

        .customizer-instructions li {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .customizer-container {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .team-pool {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            max-height: 600px;
            overflow-y: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .pool-header {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .draggable-team {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: grab;
            transition: all 0.3s ease;
            user-select: none;
            position: relative;
        }

        .draggable-team:hover {
            border-color: var(--secondary-blue);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(66, 133, 244, 0.15);
        }

        .draggable-team.dragging {
            opacity: 0.6;
            transform: rotate(2deg) scale(0.95);
            cursor: grabbing;
            z-index: 1000;
        }

        .draggable-team-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }

        .draggable-team-details {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .matchup-builder {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            min-height: 600px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .matchup-builder-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .matchup-builder-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .customizer-actions {
            display: flex;
            gap: 0.75rem;
        }

        .customizer-progress {
            margin-bottom: 2rem;
        }

        .progress-bar-custom {
            background: #e9ecef;
            border-radius: 10px;
            height: 10px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill-custom {
            background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
            height: 100%;
            transition: width 0.4s ease;
            width: 0%;
            border-radius: 10px;
        }

        .progress-text-custom {
            text-align: center;
            margin-top: 0.75rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .matchups-builder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .matchup-builder-card {
            background: #f8f9fa;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .matchup-builder-card.drag-over {
            border-color: var(--secondary-blue);
            background: rgba(66, 133, 244, 0.08);
            transform: scale(1.02);
        }

        .matchup-builder-header-text {
            text-align: center;
            font-weight: 700;
            margin-bottom: 1.25rem;
            color: var(--primary-blue);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.5rem;
            background: rgba(44, 124, 249, 0.1);
            border-radius: 8px;
        }

        .drop-slot {
            min-height: 70px;
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease;
            background: white;
        }

        .drop-slot.occupied {
            border-style: solid;
            border-color: var(--secondary-blue);
            background: rgba(66, 133, 244, 0.05);
        }

        .drop-slot.drop-target {
            border-color: var(--primary-blue);
            background: rgba(44, 124, 249, 0.1);
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(44, 124, 249, 0.2);
        }

        .drop-slot-content {
            text-align: center;
            width: 100%;
            padding: 0.75rem;
        }

        .drop-slot.empty .drop-slot-content {
            color: var(--text-muted);
            font-style: italic;
            font-size: 0.9rem;
        }

        .remove-team-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: var(--danger-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }

        .remove-team-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }

        .vs-divider-custom {
            text-align: center;
            font-weight: 700;
            color: var(--text-muted);
            margin: 0.75rem 0;
            font-size: 1.1rem;
            position: relative;
        }

        .vs-divider-custom::before,
        .vs-divider-custom::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: var(--border-color);
        }

        .vs-divider-custom::before {
            left: 0;
        }

        .vs-divider-custom::after {
            right: 0;
        }

        /* BUTTON IMPROVEMENTS */
        .tournament-page .btn {
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
        }

        .tournament-page .btn-primary {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple), var(--accent-purple));
            padding: 0.75rem 1.5rem;
        }

        .tournament-page .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(157, 78, 221, 0.3);
        }

        .tournament-page .btn-success {
            background: linear-gradient(135deg, var(--success-color), #20c997);
            padding: 0.75rem 1.5rem;
        }

        .tournament-page .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .tournament-page .btn-secondary {
            background: #6c757d;
            padding: 0.75rem 1.5rem;
        }

        .tournament-page .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        /* SEARCH INPUT IMPROVEMENTS */
        .search-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-container .input-group {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .search-container .input-group-text {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 1rem;
        }

        .search-container .form-control {
            border: none;
            padding: 1rem;
            font-size: 0.95rem;
        }

        .search-container .form-control:focus {
            box-shadow: none;
            border-color: transparent;
        }

        /* LOADING STATES */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn.loading {
            position: relative;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Bracket styles (keeping existing functional styles) */
        .tournament-page .bracket-container {
            overflow-x: auto;
            padding: 20px;
            /* Reduced from 40px 20px */
            background: #f8f9fa;
            border-radius: 12px;
        }

        .tournament-page .bracket-rounds {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            /* Changed from flex-start */
            gap: 40px;
            /* Reduced from 60px */
            min-width: fit-content;
        }

        .tournament-page .bracket-round {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            min-width: 200px;
        }

        .tournament-page .round-title {
            text-align: center;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 30px;
            color: var(--secondary-blue);
            white-space: nowrap;
        }

        .tournament-page .bracket-games {
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            gap: 20px;
            /* Add this */
        }

        .tournament-page .bracket-game {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            width: 200px;
            min-height: 80px;
            /* Add this */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            /* Remove margin-bottom: 20px; - we're using gap instead */
        }



        .tournament-page .bracket-game.completed {
            border-color: #28a745;
            box-shadow: 0 2px 12px rgba(40, 167, 69, 0.2);
        }

        .tournament-page .bracket-game.in-progress {
            border-color: #ffc107;
            box-shadow: 0 2px 12px rgba(255, 193, 7, 0.2);
        }

        .tournament-page .game-title {
            text-align: center;
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tournament-page .team-slot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
            min-height: 32px;
            font-size: 14px;
        }

        .tournament-page .team-slot:last-child {
            border-bottom: none;
        }

        .tournament-page .team-slot.winner {
            font-weight: 700;
            color: #28a745;
            background: rgba(40, 167, 69, 0.05);
            border-radius: 4px;
            margin: 1px 0;
        }

        .tournament-page .team-name {
            font-weight: 500;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
        }

        .tournament-page .team-score {
            font-weight: 700;
            font-size: 14px;
            color: var(--secondary-blue);
            min-width: 20px;
            text-align: right;
        }

        .tournament-page .bracket-game::after {
            content: '';
            position: absolute;
            right: -40px;
            top: 50%;
            width: 40px;
            height: 2px;
            background: #9e9e9e;
            transform: translateY(-50%);
        }

        .tournament-page .bracket-games {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
            gap: 60px;
            position: relative;
            min-height: 100%;
        }

        .tournament-page .bracket-round:last-child .bracket-game::after {
            display: none;
        }

        .tournament-page .bracket-round:not(:last-child) .bracket-games::before {
            content: '';
            position: absolute;
            top: 0;
            right: -40px;
            width: 2px;
            height: 100%;
            background: #9e9e9e;
            z-index: 1;
        }

        .tournament-page .bracket-round:nth-child(1) .bracket-games::after {
            top: 25%;
            height: 50%;
        }

        .tournament-page .bracket-round:nth-child(2) .bracket-games::after {
            top: 37.5%;
            height: 25%;
        }

        .tournament-page .bracket-round:nth-child(3) .bracket-games::after {
            top: 43.75%;
            height: 12.5%;
        }

        .tournament-page .bracket-round:not(:last-child)::before {
            content: '';
            position: absolute;
            top: 50%;
            right: -60px;
            width: 30px;
            height: 2px;
            background: #9e9e9e;
            transform: translateY(-1px);
            z-index: 2;
        }


        /* Score form styles */
        .tournament-page .score-form {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #f0f0f0;
        }

        .tournament-page .score-input {
            width: 50px;
            height: 30px;
            text-align: center;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 4px;
            font-weight: 600;
            font-size: 12px;
        }

        .tournament-page .score-input:focus {
            outline: none;
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
        }

        .tournament-page .score-form .btn {
            font-size: 11px;
            padding: 4px 8px;
        }

        .tournament-page .champion-banner {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin: 2rem 0;
            width: 100%;
        }

        .tournament-page .champion-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .tournament-page .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }

        .tournament-page .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Game Cards Section Styles */
        .games-section {
            margin-top: 3rem;
            background: white;
            border-radius: 16px;
            padding: 2rem;
            border: 2px solid var(--border-color);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .games-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .games-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .games-filters {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 2px solid var(--border-color);
            background: white;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn.active,
        .filter-btn:hover {
            border-color: var(--primary-blue);
            background: rgba(44, 124, 249, 0.08);
            color: var(--primary-blue);
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .game-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .game-card:hover {
            border-color: var(--secondary-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.15);
        }

        .game-card.completed {
            border-color: var(--success-color);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
        }

        .game-card.in-progress {
            border-color: var(--warning-color);
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.1);
        }

        .game-card.upcoming {
            border-color: var(--border-color);
        }

        .game-header {
            background: linear-gradient(135deg, #4E56C0, #696FC7);
            color: white;
            padding: 1rem;
            text-align: center;
            position: relative;
        }

        .game-header.completed {
            background: linear-gradient(135deg, var(--success-color), #20c997);
        }

        .game-header.in-progress {
            background: linear-gradient(135deg, var(--warning-color), #ffa726);
        }

        .game-league {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }

        .game-date {
            font-size: 0.875rem;
            opacity: 0.95;
            margin-bottom: 0.5rem;
        }

        .game-round {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .game-status-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
        }

        .teams-container {
            padding: 1.5rem;
        }

        .team-matchup {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .team-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .team-row.winner {
            background: rgba(40, 167, 69, 0.08);
            border-color: var(--success-color);
            font-weight: 700;
        }

        .team-row.loser {
            opacity: 0.7;
        }

        .team-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .team-logo {
            width: 32px;
            height: 32px;
            background: var(--light-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            color: var(--primary-blue);
            flex-shrink: 0;
        }

        .team-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .team-score {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--secondary-blue);
            min-width: 2rem;
            text-align: center;
        }

        .team-row.winner .team-score {
            color: var(--success-color);
        }

        .vs-divider {
            text-align: center;
            font-weight: 700;
            color: var(--text-muted);
            font-size: 0.875rem;
            position: relative;
            margin: 0.5rem 0;
        }

        .vs-divider::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            height: 1px;
            background: var(--border-color);
            z-index: 1;
        }

        .vs-divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }

        .game-actions {
            padding: 1rem 1.5rem;
            border-top: 1px solid #f0f0f0;
            background: #fafafa;
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .game-actions .btn {
            flex: 1;
            max-width: 120px;
        }

        .game-actions .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .tally-sheet-btn {
            background: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tally-sheet-btn:hover {
            background: var(--secondary-blue);
            color: white;
            transform: translateY(-1px);
        }

        .box-score-btn {
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .box-score-btn:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-1px);
        }

        .empty-games {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }

        .empty-games i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Score input form for incomplete games */
        .score-input-form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f0f0f0;
        }

        .score-input-form input {
            width: 60px;
            height: 32px;
            text-align: center;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .score-input-form input:focus {
            border-color: var(--secondary-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
        }

        .score-input-form button {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .tournament-page .page-header {
                padding: 1.5rem;
            }

            .tournament-page .tournament-title {
                font-size: 24px;
            }

            .tournament-page .content-section {
                padding: 1rem;
            }

            .tournament-page .team-grid {
                grid-template-columns: 1fr;
            }

            .customizer-container {
                grid-template-columns: 1fr;
            }

            .matchups-builder-grid {
                grid-template-columns: 1fr;
            }

            .customizer-actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .team-card-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .team-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .modal-footer {
                flex-direction: column;
                gap: 0.75rem;
            }

            .modal-footer .btn {
                width: 100%;
                justify-content: center;
            }

            .games-grid {
                grid-template-columns: 1fr;
            }

            .games-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .games-filters {
                justify-content: center;
            }

            .game-actions {
                flex-direction: column;
            }

            .game-actions .btn {
                max-width: none;
            }

            .selection-header .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .selection-actions {
                justify-content: center;
                width: 100%;
            }

            .selection-actions .btn {
                flex: 1;
                min-width: 0;
            }

            .team-card-content {
                flex-direction: row;
                align-items: center;
                gap: 0.75rem;
            }

            .checkbox-container {
                order: -1;
            }

            .team-details {
                flex-direction: column;
                gap: 0.25rem;
            }

            .status-badge {
                font-size: 0.65rem;
                padding: 0.3rem 0.6rem;
            }
        }

        @media (max-width: 576px) {
            .selection-actions {
                flex-direction: column;
            }

            .selection-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .games-section {
                padding: 1rem;
                margin-top: 2rem;
            }

            .teams-container {
                padding: 1rem;
            }

            .team-row {
                padding: 0.5rem;
            }

            .team-info {
                gap: 0.5rem;
            }

            .team-logo {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }
        }

        .start-game-btn {
            background: var(--success-color);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .start-game-btn:hover {
            background: #218838;
            color: white;
            transform: translateY(-1px);
        }

        .game-header.in-progress {
            background: linear-gradient(135deg, #FF5722, #FF9800);
        }

        .game-card.in-progress .game-status-badge {
            background: rgba(255, 87, 34, 0.9);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .start-game-btn[style*="background: #FF9800"] {
            background: linear-gradient(135deg, #FF9800, #F57C00) !important;
        }

        /* --- Bracket layout improvements & disable old pseudo connectors --- */
        .tournament-page .bracket-container {
            padding: 20px 12px;
            /* smaller padding to make bracket more 'flat' */
            position: relative;
            /* for svg overlay */
            overflow-x: auto;
            /* keep horizontal scroll */
        }

        .tournament-page .bracket-flex {
            gap: 24px !important;
            /* reduce horizontal gap to make layout flatter */
            justify-content: flex-start !important;
            /* move bracket to the left */
            align-items: stretch !important;
        }

        /* narrower rounds + flatter vertical spacing */
        .tournament-page .bracket-round {
            min-width: 140px !important;
            align-items: flex-start !important;
        }

        .tournament-page .bracket-games {
            gap: 18px !important;
            /* less vertical space between games (flatter) */
            align-items: flex-start !important;
            position: relative;
        }

        /* make game cards narrower */
        .tournament-page .bracket-game {
            min-width: 140px !important;
            width: 140px !important;
            min-height: 64px !important;
            padding: 8px !important;
            box-sizing: border-box;
            z-index: 2;
            /* keep games above SVG lines */
            align-items: flex-start !important;
        }

        /* hide the old pseudo-element connectors so they don't conflict */
        .tournament-page .bracket-game::after,
        .tournament-page .bracket-round:not(:last-child)::before,
        .tournament-page .bracket-round:not(:last-child) .bracket-games::after,
        .tournament-page .bracket-round .bracket-games::before {
            display: none !important;
        }

        /* svg overlay that will draw lines */
        .tournament-page .bracket-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            /* under the .bracket-game (which has z-index:2) */
        }

        /* small responsive tweak */
        @media (max-width: 992px) {
            .tournament-page .bracket-flex {
                gap: 16px !important;
            }

            .tournament-page .bracket-game {
                min-width: 120px !important;
                width: 120px !important;
            }

            .tournament-page .bracket-games {
                gap: 12px !important;
            }
        }

        /* Add these new styles for round-robin + playoff */
        .badge-group {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .playoff-bracket-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 2rem;
            border: 2px solid #dee2e6;
        }

        .playoff-rounds {
            display: flex;
            gap: 3rem;
            justify-content: center;
            align-items: flex-start;
        }

        .playoff-round {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 200px;
        }

        .playoff-round .round-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            color: var(--primary-blue);
            text-align: center;
        }

        .playoff-games {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .playoff-game {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            min-width: 180px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .playoff-game.completed {
            border-color: var(--success-color);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
        }

        .playoff-game.ready {
            border-color: var(--warning-color);
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
        }

        .playoff-game.finals {
            border-color: var(--primary-blue);
            box-shadow: 0 4px 15px rgba(44, 124, 249, 0.2);
        }

        .playoff-game.third-place {
            border-color: #fd7e14;
            box-shadow: 0 4px 15px rgba(253, 126, 20, 0.2);
        }

        .playoff-game .game-header {
            background: var(--primary-blue);
            color: white;
            padding: 0.5rem;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .playoff-game.finals .game-header {
            background: linear-gradient(135deg, #ffd700, #ff8c00);
            color: #000;
        }

        .playoff-game.third-place .game-header {
            background: #fd7e14;
        }

        .playoff-game .team-slot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }

        .playoff-game .team-slot:last-child {
            border-bottom: none;
        }

        .playoff-game .team-slot.winner {
            font-weight: 700;
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .playoff-game .team-slot .team-name {
            flex: 1;
            font-weight: 500;
        }

        .playoff-game .team-slot .score {
            font-weight: 700;
            min-width: 30px;
            text-align: center;
            color: var(--primary-blue);
        }

        .playoff-game .team-slot.winner .score {
            color: var(--success-color);
        }

        .final-results-banner {
            background: linear-gradient(135deg, #4E56C0, #696FC7);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-top: 2rem;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            text-align: center;
        }

        .result-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .result-position {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .result-team {
            font-size: 1.3rem;
            font-weight: 700;
        }

        .result-item.champion {
            background: rgba(255, 215, 0, 0.2);
            border: 2px solid rgba(255, 215, 0, 0.5);
        }

        .phase-progress {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .section-header {
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .standings-table-container {
            overflow-x: auto;
        }

        .standings-table-container table {
            min-width: 600px;
            font-size: 0.9rem;
        }

        .standings-table-container th {
            white-space: nowrap;
            font-size: 0.8rem;
            padding: 0.5rem 0.3rem;
        }

        .standings-table-container td {
            padding: 0.5rem 0.3rem;
            white-space: nowrap;
        }

        .round-robin-standings {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .playoff-rounds {
                flex-direction: column;
                gap: 2rem;
                align-items: center;
            }

            .results-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .badge-group {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 600;
            transition: all 0.3s;
        }

        .filter-tab.active {
            color: var(--primary-purple) !important;
            border-bottom-color: var(--primary-purple) !important;
        }

        .filter-tab:hover {
            background: rgba(157, 78, 221, 0.05);
        }

        /* Team card visibility */
        .team-card-wrapper.hidden {
            display: none !important;
        }

        .no-teams-message {
            padding: 2rem;
        }

        .bracket-game.bye {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 2px dashed #2196F3;
        }

        .bye-badge {
            background: #2196F3;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .sport-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .sport-badge.volleyball {
            background: rgba(255, 152, 0, 0.2);
            color: #FF9800;
            border: 1px solid rgba(255, 152, 0, 0.3);
        }

        .sport-badge.basketball {
            background: rgba(33, 150, 243, 0.2);
            color: #2196F3;
            border: 1px solid rgba(33, 150, 243, 0.3);
        }
    </style>
@endpush

@section('content')

    <div class="tournament-page">
        <div class="main-container">

            <!-- Tournament Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="tournament-title">{{ $tournament->name }}</h1>
                        <p class="tournament-subtitle">
                            {{ $tournament->sport->sports_name ?? 'N/A' }} • {{ $tournament->division }} •
                            {{ $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('F j, Y') : 'Date TBD' }}
                        </p>
                    </div>
                    <a href="{{ route('tournaments.index') }}" class="back-btn">
                        <i class="bi bi-arrow-left"></i>
                        Back to Tournaments
                    </a>
                </div>
            </div>

            <div class="content-section">

                <!-- Tournament Info -->
                <div class="info-card">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="section-title">Tournament Information</h5>
                            <p><strong>Teams Registered:</strong> {{ $tournament->teams->count() }}</p>
                            <p><strong>Status:</strong>
                                @if ($tournament->brackets->isNotEmpty())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning">Setup</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="section-title">Quick Actions</h5>
                            @if ($tournament->teams->count() >= 3)
                                @if ($tournament->brackets->isEmpty())
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createBracketModal">
                                        <i class="bi bi-diagram-3"></i>
                                        Create Bracket
                                    </button>
                                @endif
                            @else
                                <p class="text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Add at least 3 teams to create a bracket ({{ $tournament->teams->count() }}/3 teams
                                    registered)
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Team Management Section -->
                <div class="info-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="section-title mb-0">Team Management ({{ $tournament->teams->count() }})</h5>
                        @if (isset($availableTeams) && $availableTeams->count() > 0)
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignTeamModal">
                                <i class="bi bi-plus-circle"></i>
                                Add Team
                            </button>
                        @endif
                    </div>

                    <!-- Current Teams -->
                    @if ($tournament->teams->count() > 0)
                        <div class="team-grid mb-3">
                            @foreach ($tournament->teams as $team)
                                <div class="team-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="team-name">{{ $team->team_name }}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-person"></i>
                                            Coach: {{ $team->coach_name ?? 'N/A' }}
                                        </small>
                                    </div>
                                    @if ($tournament->brackets()->where('status', 'active')->doesntExist())
                                        <form action="{{ route('tournaments.remove-team', [$tournament->id, $team->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Remove {{ $team->team_name }} from this tournament?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p>No teams assigned yet.</p>
                            @if (isset($availableTeams) && $availableTeams->count() > 0)
                            @else
                                <p class="text-muted small">
                                    <a href="{{ route('teams.index') }}" class="text-decoration-none">
                                        Create teams first in the Teams section →
                                    </a>
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Available Teams Info -->
                    @if (isset($availableTeams) && $availableTeams->count() > 0)
                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i>
                                {{ $availableTeams->count() }} {{ $tournament->sport->sports_name ?? 'Sport' }} teams
                                available to assign.
                            </small>
                        </div>
                    @endif
                </div>
                @foreach ($tournament->brackets as $bracket)
                    <div class="info-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="section-title mb-1">{{ $bracket->name }}</h5>
                                <div class="badge-group">
                                    <span
                                        class="badge bg-{{ $bracket->status === 'completed' ? 'success' : ($bracket->status === 'active' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($bracket->status) }}
                                    </span>
                                    <span
                                        class="badge bg-info ms-2">{{ ucwords(str_replace('-', ' ', $bracket->type)) }}</span>

                                    @if ($bracket->type === 'round-robin-playoff')
                                        <span class="badge bg-primary ms-2">
                                            {{ ucfirst($bracket->getCurrentPhase()) }} Phase
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if ($bracket->status === 'setup' && $bracket->games->isEmpty())
                                <div class="d-flex gap-2">
                                    @if ($bracket->type === 'single-elimination')
                                        <button class="btn btn-primary"
                                            onclick="toggleBracketCustomizer({{ $bracket->id }})">
                                            <i class="bi bi-gear"></i>
                                            Customize Bracket
                                        </button>
                                    @endif
                                    <form action="{{ route('brackets.generate', $bracket) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-play-circle"></i>
                                            Generate Tournament
                                        </button>
                                    </form>
                                </div>

                                {{-- Keep the existing Bracket Customizer for Single Elimination --}}
                                @if ($bracket->type === 'single-elimination')
                                    <div class="bracket-customizer" id="bracketCustomizer{{ $bracket->id }}">
                                        <!-- Instructions removed -->
                                        <div class="customizer-progress">
                                            <div class="progress-bar-custom">
                                                <div class="progress-fill-custom" id="progressFill{{ $bracket->id }}">
                                                </div>
                                            </div>
                                            <div class="progress-text-custom" id="progressText{{ $bracket->id }}">
                                                0 of {{ $tournament->teams->count() }} teams placed
                                            </div>
                                        </div>

                                        <div class="customizer-container">
                                            <div class="team-pool">
                                                <div class="pool-header">
                                                    <i class="bi bi-people-fill"></i>
                                                    Available Teams
                                                </div>
                                                <div id="teamPool{{ $bracket->id }}">
                                                    @foreach ($tournament->teams as $team)
                                                        <div class="draggable-team" draggable="true"
                                                            data-team-id="{{ $team->id }}"
                                                            data-bracket-id="{{ $bracket->id }}">
                                                            <div class="draggable-team-name">{{ $team->team_name }}</div>
                                                            <div class="draggable-team-details">Coach:
                                                                {{ $team->coach_name ?? 'N/A' }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="matchup-builder">
                                                <div class="matchup-builder-header">
                                                    <div class="matchup-builder-title">First Round Matchups</div>
                                                    <div class="customizer-actions">
                                                        <button class="btn btn-secondary btn-sm"
                                                            onclick="resetCustomizer({{ $bracket->id }})">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                            Reset
                                                        </button>
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="autoFillSlots({{ $bracket->id }})">
                                                            <i class="bi bi-magic"></i>
                                                            Auto Fill
                                                        </button>
                                                        <button class="btn btn-success btn-sm"
                                                            onclick="generateCustomBracket({{ $bracket->id }})"
                                                            id="generateCustomBtn{{ $bracket->id }}" disabled>
                                                            <i class="bi bi-play-circle"></i>
                                                            Generate Custom Bracket
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="matchups-builder-grid" id="matchupsGrid{{ $bracket->id }}">
                                                    @for ($i = 0; $i < floor($tournament->teams->count() / 2); $i++)
                                                        <div class="matchup-builder-card">
                                                            <div class="matchup-builder-header-text">Game
                                                                {{ $i + 1 }}</div>
                                                            <div class="drop-slot empty"
                                                                data-matchup="{{ $i }}" data-slot="0"
                                                                data-bracket-id="{{ $bracket->id }}">
                                                                <div class="drop-slot-content">Drop team here</div>
                                                            </div>
                                                            <div class="vs-divider-custom">VS</div>
                                                            <div class="drop-slot empty"
                                                                data-matchup="{{ $i }}" data-slot="1"
                                                                data-bracket-id="{{ $bracket->id }}">
                                                                <div class="drop-slot-content">Drop team here</div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        @if ($bracket->games->count() > 0)

                            @if ($bracket->type === 'round-robin-playoff')
                                <!-- HYBRID TOURNAMENT: Round-Robin + Playoffs -->

                                <!-- Overall Progress -->
                                <div class="tournament-progress mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Overall Tournament Progress</small>
                                        <small class="text-muted">{{ $bracket->getCompletionPercentage() }}%
                                            Complete</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ $bracket->getCompletionPercentage() }}%"></div>
                                    </div>
                                </div>

                                <!-- Phase Progress Bars -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="phase-progress">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Group Stage</small>
                                                <small
                                                    class="text-muted">{{ $bracket->getRoundRobinCompletionPercentage() }}%</small>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success"
                                                    style="width: {{ $bracket->getRoundRobinCompletionPercentage() }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="phase-progress">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Playoffs</small>
                                                <small
                                                    class="text-muted">{{ $bracket->getPlayoffCompletionPercentage() }}%</small>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-warning"
                                                    style="width: {{ $bracket->getPlayoffCompletionPercentage() }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Round-Robin Standings -->
                                <div class="round-robin-section mb-4">
                                    <div class="section-header">
                                        <h6 class="section-title">
                                            <i class="bi bi-list-ol"></i>
                                            Group Stage Standings
                                            @if ($bracket->isRoundRobinPhaseComplete())
                                                <span class="badge bg-success ms-2">Complete</span>
                                            @endif
                                        </h6>
                                    </div>

                                    @if ($bracket->getRoundRobinStandings()->isNotEmpty())
                                        <div class="standings-table-container">
                                            <table class="table table-striped">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Pos</th>
                                                        <th>Team</th>
                                                        <th>GP</th>
                                                        <th>W</th>
                                                        <th>L</th>
                                                        <th>Win%</th>

                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($bracket->getRoundRobinStandings() as $index => $standing)
                                                        <tr
                                                            class="{{ $standing['playoff_qualified'] ? 'table-success' : ($index < 4 ? 'table-warning' : '') }}">
                                                            <td>
                                                                <strong>{{ $index + 1 }}</strong>
                                                                @if ($standing['playoff_qualified'])
                                                                    <i class="bi bi-check-circle-fill text-success ms-1"
                                                                        title="Qualified for Playoffs"></i>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <strong>{{ $standing['team']->team_name }}</strong>
                                                            </td>
                                                            <td>{{ $standing['games_played'] }}</td>
                                                            <td class="text-success fw-bold">{{ $standing['wins'] }}</td>
                                                            <td class="text-danger">{{ $standing['losses'] }}</td>
                                                            <td>{{ number_format($standing['win_percentage'], 1) }}%</td>


                                                            <td>
                                                                @if ($bracket->isRoundRobinPhaseComplete())
                                                                    @if ($standing['playoff_qualified'])
                                                                        <span class="badge bg-success">Qualified</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">Eliminated</span>
                                                                    @endif
                                                                @else
                                                                    @if ($standing['remaining_games'] > 0)
                                                                        <span
                                                                            class="badge bg-warning text-dark">{{ $standing['remaining_games'] }}
                                                                            left</span>
                                                                    @else
                                                                        <span class="badge bg-info">Done</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="standings-legend mt-2">
                                            <small class="text-muted">
                                                <strong>Legend:</strong> GP = Games Played, W = Wins, L = Losses, Win% = Win
                                                Percentage,
                                                PF = Points For, PA = Points Against, Diff = Point Difference
                                                @if ($bracket->isRoundRobinPhaseComplete())
                                                    <br><strong>Top 4 teams qualify for playoffs</strong>
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </div>

                                <!-- Playoff Bracket -->
                                @if ($bracket->isRoundRobinPhaseComplete() || $bracket->hasPlayoffsStarted())
                                    <div class="playoff-section">
                                        <div class="section-header mb-3">
                                            <h6 class="section-title">
                                                <i class="bi bi-trophy"></i>
                                                Playoff Bracket
                                                @if ($bracket->getCurrentPhase() === 'playoff')
                                                    <span class="badge bg-warning ms-2">In Progress</span>
                                                @elseif($bracket->getCurrentPhase() === 'completed')
                                                    <span class="badge bg-success ms-2">Complete</span>
                                                @endif
                                            </h6>
                                        </div>

                                        <div class="playoff-bracket-container">
                                            <div class="playoff-rounds">

                                                <!-- Semifinals -->
                                                <div class="playoff-round">
                                                    <div class="round-title">Semifinals</div>
                                                    <div class="playoff-games">
                                                        @foreach ($bracket->getPlayoffStructure()['semifinals'] as $semifinal)
                                                            <div
                                                                class="playoff-game {{ $semifinal->status === 'completed' ? 'completed' : ($semifinal->isReady() ? 'ready' : 'pending') }}">
                                                                <div class="game-header">
                                                                    <small>Semifinal {{ $semifinal->match_number }}</small>
                                                                </div>
                                                                <div
                                                                    class="team-slot {{ $semifinal->winner_id === $semifinal->team1_id ? 'winner' : '' }}">
                                                                    <span
                                                                        class="team-name">{{ $semifinal->team1 ? $semifinal->team1->team_name : 'TBD' }}</span>
                                                                    <span
                                                                        class="score">{{ $semifinal->team1_score ?? '-' }}</span>
                                                                </div>
                                                                <div
                                                                    class="team-slot {{ $semifinal->winner_id === $semifinal->team2_id ? 'winner' : '' }}">
                                                                    <span
                                                                        class="team-name">{{ $semifinal->team2 ? $semifinal->team2->team_name : 'TBD' }}</span>
                                                                    <span
                                                                        class="score">{{ $semifinal->team2_score ?? '-' }}</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Finals -->
                                                <div class="playoff-round">
                                                    <div class="round-title">Finals</div>
                                                    <div class="playoff-games">

                                                        <!-- Championship Final -->
                                                        @php $finals = $bracket->getPlayoffStructure()['finals']; @endphp
                                                        <div
                                                            class="playoff-game finals {{ $finals->status === 'completed' ? 'completed' : ($finals->isReady() ? 'ready' : 'pending') }}">
                                                            <div class="game-header">
                                                                <small>Championship Final</small>
                                                            </div>
                                                            <div
                                                                class="team-slot {{ $finals->winner_id === $finals->team1_id ? 'winner' : '' }}">
                                                                <span
                                                                    class="team-name">{{ $finals->team1 ? $finals->team1->team_name : 'Semifinal 1 Winner' }}</span>
                                                                <span
                                                                    class="score">{{ $finals->team1_score ?? '-' }}</span>
                                                            </div>
                                                            <div
                                                                class="team-slot {{ $finals->winner_id === $finals->team2_id ? 'winner' : '' }}">
                                                                <span
                                                                    class="team-name">{{ $finals->team2 ? $finals->team2->team_name : 'Semifinal 2 Winner' }}</span>
                                                                <span
                                                                    class="score">{{ $finals->team2_score ?? '-' }}</span>
                                                            </div>
                                                        </div>

                                                        <!-- 3rd Place Game -->
                                                        @php $thirdPlace = $bracket->getPlayoffStructure()['third_place']; @endphp
                                                        <div
                                                            class="playoff-game third-place {{ $thirdPlace->status === 'completed' ? 'completed' : ($thirdPlace->isReady() ? 'ready' : 'pending') }}">
                                                            <div class="game-header">
                                                                <small>3rd Place Playoff</small>
                                                            </div>
                                                            <div
                                                                class="team-slot {{ $thirdPlace->winner_id === $thirdPlace->team1_id ? 'winner' : '' }}">
                                                                <span
                                                                    class="team-name">{{ $thirdPlace->team1 ? $thirdPlace->team1->team_name : 'Semifinal 1 Loser' }}</span>
                                                                <span
                                                                    class="score">{{ $thirdPlace->team1_score ?? '-' }}</span>
                                                            </div>
                                                            <div
                                                                class="team-slot {{ $thirdPlace->winner_id === $thirdPlace->team2_id ? 'winner' : '' }}">
                                                                <span
                                                                    class="team-name">{{ $thirdPlace->team2 ? $thirdPlace->team2->team_name : 'Semifinal 2 Loser' }}</span>
                                                                <span
                                                                    class="score">{{ $thirdPlace->team2_score ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Final Results -->
                                @if ($bracket->isCompleted())
                                    <div class="final-results-banner">
                                        <div class="results-grid">
                                            <div class="result-item champion">
                                                <div class="result-position">🏆 Champion</div>
                                                <div class="result-team">
                                                    {{ $bracket->getChampion()?->team_name ?? 'TBD' }}</div>
                                            </div>
                                            <div class="result-item runner-up">
                                                <div class="result-position">🥈 Runner-up</div>
                                                <div class="result-team">
                                                    {{ $bracket->getRunnerUp()?->team_name ?? 'TBD' }}</div>
                                            </div>
                                            <div class="result-item third-place">
                                                <div class="result-position">🥉 3rd Place</div>
                                                <div class="result-team">
                                                    {{ $bracket->getThirdPlace()?->team_name ?? 'TBD' }}</div>
                                            </div>
                                            <div class="result-item group-winner">
                                                <div class="result-position">📊 Group Winner</div>
                                                <div class="result-team">
                                                    {{ $bracket->getRoundRobinWinner()?->team_name ?? 'TBD' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($bracket->type === 'round-robin')
                                <!-- PURE ROUND-ROBIN TOURNAMENT -->

                                <!-- Progress Bar -->
                                <div class="bracket-progress mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Tournament Progress</small>
                                        <small class="text-muted">{{ $bracket->getCompletionPercentage() }}%
                                            Complete</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ $bracket->getCompletionPercentage() }}%"></div>
                                    </div>
                                </div>

                                <!-- Round Robin Standings -->
                                <div class="round-robin-standings">
                                    <h6 class="section-title">
                                        <i class="bi bi-trophy"></i>
                                        Current Standings
                                    </h6>

                                    @if ($bracket->getRoundRobinStandings()->isNotEmpty())
                                        <div class="standings-table-container">
                                            <table class="table table-striped">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Pos</th>
                                                        <th>Team</th>
                                                        <th>GP</th>
                                                        <th>W</th>
                                                        <th>L</th>
                                                        <th>Win%</th>

                                                        <th>Rem</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($bracket->getRoundRobinStandings() as $index => $standing)
                                                        <tr
                                                            class="{{ $index === 0 && $bracket->isCompleted() ? 'table-success' : '' }}">
                                                            <td>
                                                                <strong>{{ $index + 1 }}</strong>
                                                                @if ($index === 0 && $bracket->isCompleted())
                                                                    <i class="bi bi-trophy-fill text-warning ms-1"
                                                                        title="Champion"></i>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <strong>{{ $standing['team']->team_name }}</strong>
                                                            </td>
                                                            <td>{{ $standing['games_played'] }}</td>
                                                            <td class="text-success fw-bold">{{ $standing['wins'] }}</td>
                                                            <td class="text-danger">{{ $standing['losses'] }}</td>
                                                            <td>{{ number_format($standing['win_percentage'], 1) }}%</td>
                                                            <td>{{ $standing['points_for'] }}</td>
                                                            <td>{{ $standing['points_against'] }}</td>
                                                            <td
                                                                class="{{ $standing['point_difference'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                                {{ $standing['point_difference'] > 0 ? '+' : '' }}{{ $standing['point_difference'] }}
                                                            </td>
                                                            <td>
                                                                @if ($standing['remaining_games'] > 0)
                                                                    <span
                                                                        class="badge bg-warning text-dark">{{ $standing['remaining_games'] }}</span>
                                                                @else
                                                                    <span class="badge bg-success">Done</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="standings-legend mt-3">
                                            <small class="text-muted">
                                                <strong>Legend:</strong>
                                                GP = Games Played, W = Wins, L = Losses, Win% = Win Percentage,
                                                PF = Points For, PA = Points Against, Diff = Point Difference, Rem =
                                                Remaining Games
                                            </small>
                                        </div>
                                    @endif
                                </div>

                                @if ($bracket->isCompleted())
                                    <div class="champion-banner">
                                        <h5 class="champion-title">🏆 Round Robin Champion</h5>
                                        <p class="mb-0" style="font-size: 18px; font-weight: 600;">
                                            {{ $bracket->getChampion()?->team_name ?? 'TBD' }}
                                        </p>
                                    </div>
                                @endif
                            @else
                                <!-- SINGLE ELIMINATION TOURNAMENT -->

                                <!-- Keep your existing bracket display code exactly as it is -->
                                <div class="bracket-container"
                                    style="overflow-x:auto; padding:20px 12px; position:relative;">
                                    <svg class="bracket-svg" aria-hidden="true"></svg>
                                    <div class="bracket-flex"
                                        style="display:flex; gap:24px; justify-content:flex-start; align-items:stretch;">
                                        @for ($round = 1; $round <= $bracket->getTotalRounds(); $round++)
                                            <div class="bracket-round"
                                                style="display:flex; flex-direction:column; align-items:flex-start; min-width:140px; position:relative;">
                                                <div class="round-title"
                                                    style="margin-bottom:20px; font-weight:bold; font-size:1.1rem;">
                                                    @if ($round == $bracket->getTotalRounds())
                                                        🏆 Finals
                                                    @elseif($round == $bracket->getTotalRounds() - 1)
                                                        🥇 Semifinals
                                                    @elseif($round == $bracket->getTotalRounds() - 2)
                                                        🏅 Quarterfinals
                                                    @else
                                                        Round {{ $round }}
                                                    @endif
                                                </div>

                                                <div class="bracket-games"
                                                    style="display:flex; flex-direction:column; gap:18px; align-items:flex-start; position:relative;">
                                                    @foreach ($bracket->gamesByRound($round) as $game)
                                                        @if ($game->is_bye)
                                                            {{-- BYE GAME --}}
                                                            <div class="bracket-game bye"
                                                                style="background:linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius:10px; border:2px dashed #2196F3; min-width:160px; min-height:70px; position:relative; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:8px;">
                                                                <div class="game-title"
                                                                    style="font-weight:600; margin-bottom:6px; font-size:0.75rem; color:#1976D2;">
                                                                    Game {{ $game->match_number }}
                                                                </div>
                                                                <div class="text-center">
                                                                    <div class="bye-badge"
                                                                        style="background:#2196F3; color:white; padding:0.25rem 0.5rem; border-radius:12px; font-size:0.65rem; font-weight:600; display:inline-block; margin-bottom:4px;">
                                                                        <i class="bi bi-fast-forward"></i> BYE
                                                                    </div>
                                                                    <div
                                                                        style="font-weight:600; font-size:0.8rem; color:#1976D2;">
                                                                        {{ $game->team1 ? $game->team1->team_name : 'Winner TBD' }}
                                                                    </div>
                                                                    <small class="text-muted"
                                                                        style="font-size:0.65rem;">Auto-Advance</small>
                                                                </div>
                                                                <div class="bracket-connector"
                                                                    style="position:absolute; right:-40px; top:50%; width:40px; height:2px; background:#2196F3; z-index:1; display:@if ($round < $bracket->getTotalRounds()) block @else none @endif;">
                                                                </div>
                                                            </div>
                                                        @else
                                                            {{-- REGULAR GAME --}}
                                                            <div class="bracket-game {{ $game->status === 'in_progress' ? 'in-progress' : ($game->isCompleted() ? 'completed' : 'upcoming') }}"
                                                                style="background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(44,124,249,0.07); border:2px solid #dee2e6; min-width:160px; min-height:70px; position:relative; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                                                <div class="game-title"
                                                                    style="font-weight:600; margin-bottom:6px;">Game
                                                                    {{ $game->match_number }}</div>

                                                                @if ($game->isReady())
                                                                    <div class="team-slot {{ $game->winner_id === $game->team1_id ? 'winner' : '' }}"
                                                                        style="display:flex; justify-content:space-between; align-items:center; width:100%; padding:2px 10px;">
                                                                        <span class="team-name"
                                                                            style="font-weight:500;">{{ $game->team1->team_name }}</span>
                                                                        <span class="team-score"
                                                                            style="font-weight:700; color:#4285f4;">{{ $game->team1_score ?? '-' }}</span>
                                                                    </div>
                                                                    <div class="team-slot {{ $game->winner_id === $game->team2_id ? 'winner' : '' }}"
                                                                        style="display:flex; justify-content:space-between; align-items:center; width:100%; padding:2px 10px;">
                                                                        <span class="team-name"
                                                                            style="font-weight:500;">{{ $game->team2->team_name }}</span>
                                                                        <span class="team-score"
                                                                            style="font-weight:700; color:#4285f4;">{{ $game->team2_score ?? '-' }}</span>
                                                                    </div>

                                                                    @if (!$game->isCompleted() && $game->status !== 'in_progress')
                                                                        <form action="{{ route('games.update', $game) }}"
                                                                            method="POST" class="score-form">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <!-- form content -->
                                                                        </form>
                                                                    @endif
                                                                @else
                                                                    <div class="team-slot"
                                                                        style="display:flex; justify-content:space-between; align-items:center; width:100%; padding:2px 10px;">
                                                                        <span
                                                                            class="team-name text-muted">{{ $game->team1 ? $game->team1->team_name : 'TBD' }}</span>
                                                                        <span class="team-score">-</span>
                                                                    </div>
                                                                    <div class="team-slot"
                                                                        style="display:flex; justify-content:space-between; align-items:center; width:100%; padding:2px 10px;">
                                                                        <span
                                                                            class="team-name text-muted">{{ $game->team2 ? $game->team2->team_name : 'TBD' }}</span>
                                                                        <span class="team-score">-</span>
                                                                    </div>
                                                                @endif

                                                                <div class="bracket-connector"
                                                                    style="position:absolute; right:-40px; top:50%; width:40px; height:2px; background:#dee2e6; z-index:1; display:@if ($round < $bracket->getTotalRounds()) block @else none @endif;">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                @if ($bracket->isCompleted())
                                    <div class="champion-banner">
                                        <h5 class="champion-title">🏆 Tournament Champion</h5>
                                        <p class="mb-0" style="font-size: 18px; font-weight: 600;">
                                            {{ $bracket->getChampion()?->team_name ?? 'TBD' }}
                                        </p>
                                    </div>
                                @endif
                            @endif

                            <!-- Keep your existing Games Section exactly as it is -->
                            @if ($bracket->games->count() > 0)
                                <div class="games-section">
                                    <div class="games-header">
                                        <h5 class="games-title">
                                            <i class="bi bi-calendar-event"></i>
                                            Tournament Games
                                        </h5>
                                        <div class="games-filters">
                                            <button class="filter-btn active" data-filter="all">
                                                <i class="bi bi-list"></i>
                                                All Games
                                            </button>
                                            <button class="filter-btn" data-filter="upcoming">
                                                <i class="bi bi-clock"></i>
                                                Upcoming
                                            </button>
                                            <button class="filter-btn" data-filter="in-progress">
                                                <i class="bi bi-play-circle"></i>
                                                In Progress
                                            </button>
                                            <button class="filter-btn" data-filter="completed">
                                                <i class="bi bi-check-circle"></i>
                                                Completed
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Keep all your existing games grid HTML exactly as is -->
                                    <div class="games-grid" id="gamesGrid">
                                        @foreach ($bracket->games->sortBy(['round', 'match_number']) as $game)
                                            @if ($game->is_bye)
                                                {{-- BYE GAME CARD --}}
                                                <div class="game-card bye" data-status="completed"
                                                    data-round="{{ $game->round }}">
                                                    <div class="game-header"
                                                        style="background: linear-gradient(135deg, #2196F3, #1976D2);">
                                                        <div class="game-league">{{ $tournament->name }}</div>
                                                        <div class="game-date">
                                                            {{ $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('M j, Y') : 'Date TBD' }}
                                                        </div>
                                                        <h6 class="game-round">
                                                            @if ($game->round == $bracket->getTotalRounds())
                                                                🏆 Finals
                                                            @elseif($game->round == $bracket->getTotalRounds() - 1)
                                                                🥇 Semifinals
                                                            @elseif($game->round == $bracket->getTotalRounds() - 2)
                                                                🏅 Quarterfinals
                                                            @else
                                                                Round {{ $game->round }}
                                                            @endif
                                                            - Game {{ $game->match_number }}
                                                        </h6>
                                                        <div class="game-status-badge">
                                                            <i class="bi bi-fast-forward"></i> BYE
                                                        </div>
                                                    </div>

                                                    <div class="teams-container">
                                                        @if ($game->team1)
                                                            <div class="text-center py-4">
                                                                <div class="bye-badge mb-3"
                                                                    style="background:#2196F3; color:white; padding:0.5rem 1rem; border-radius:20px; display:inline-block; font-weight:600;">
                                                                    <i class="bi bi-fast-forward"></i> BYE GAME
                                                                </div>
                                                                <div class="team-info"
                                                                    style="display:flex; flex-direction:column; align-items:center;">
                                                                    <div class="team-logo"
                                                                        style="width:48px; height:48px; background:#e3f2fd; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.1rem; color:#1976D2; margin-bottom:0.5rem;">
                                                                        {{ strtoupper(substr($game->team1->team_name, 0, 2)) }}
                                                                    </div>
                                                                    <h5 class="mb-1">
                                                                        <strong>{{ $game->team1->team_name }}</strong>
                                                                    </h5>
                                                                    <p class="text-muted mb-0">Advances Automatically to
                                                                        Round {{ $game->round + 1 }}</p>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-center py-4">
                                                                <div class="bye-badge mb-3"
                                                                    style="background:#2196F3; color:white; padding:0.5rem 1rem; border-radius:20px; display:inline-block; font-weight:600;">
                                                                    <i class="bi bi-fast-forward"></i> BYE PLACEHOLDER
                                                                </div>
                                                                <p class="text-muted">Waiting for previous round winner</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                {{-- REGULAR GAME CARD --}}
                                                <div class="game-card {{ $game->status }}"
                                                    data-status="{{ $game->status }}"
                                                    data-round="{{ $game->round }}">
                                                    {{-- Add sport badge to game header --}}
                                                        <div class="game-header {{ $game->status === 'completed' ? 'completed' : ($game->status === 'in_progress' ? 'in-progress' : 'upcoming') }}">
                                                            <div class="game-league">
                                                                {{ $tournament->name }}
                                                                {{-- ADD THIS SPORT BADGE --}}
                                                                @if($game->isVolleyball())
                                                                    <span class="sport-badge volleyball">🏐 Volleyball</span>
                                                                @elseif($game->isBasketball())
                                                                    <span class="sport-badge basketball">🏀 Basketball</span>
                                                                @endif
                                                            </div>
                                                            <div class="game-date">
                                                                {{ $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('M j, Y') : 'Date TBD' }}
                                                            </div>
                                                            <h6 class="game-round">
                                                                @if ($game->round == ($game->bracket ? $game->bracket->getTotalRounds() : 0))
                                                                    🏆 Finals
                                                                @elseif($game->round == ($game->bracket ? $game->bracket->getTotalRounds() - 1 : 0))
                                                                    🥇 Semifinals
                                                                @elseif($game->round == ($game->bracket ? $game->bracket->getTotalRounds() - 2 : 0))
                                                                    🏅 Quarterfinals
                                                                @else
                                                                    Round {{ $game->round }}
                                                                @endif
                                                                - Game {{ $game->match_number }}
                                                            </h6>
                                                            <div class="game-status-badge">
                                                                @if ($game->status === 'completed')
                                                                    ✅ Completed
                                                                @elseif($game->status === 'in_progress')
                                                                    🔴 Live
                                                                @else
                                                                    {{ ucfirst(str_replace('-', ' ', $game->status)) }}
                                                                @endif
                                                            </div>
                                                        </div>

                                                    <div class="teams-container">
                                                        @if ($game->isReady())
                                                            <div class="team-matchup">
                                                                <div
                                                                    class="team-row {{ $game->winner_id === $game->team1_id ? 'winner' : ($game->isCompleted() ? 'loser' : '') }}">
                                                                    <div class="team-info">
                                                                        <div class="team-logo">
                                                                            {{ strtoupper(substr($game->team1->team_name, 0, 2)) }}
                                                                        </div>
                                                                        <div class="team-name">
                                                                            {{ $game->team1->team_name }}</div>
                                                                    </div>
                                                                    <div class="team-score">
                                                                        {{ $game->team1_score ?? '-' }}</div>
                                                                </div>
                                                                <div class="vs-divider"><span>VS</span></div>
                                                                <div
                                                                    class="team-row {{ $game->winner_id === $game->team2_id ? 'winner' : ($game->isCompleted() ? 'loser' : '') }}">
                                                                    <div class="team-info">
                                                                        <div class="team-logo">
                                                                            {{ strtoupper(substr($game->team2->team_name, 0, 2)) }}
                                                                        </div>
                                                                        <div class="team-name">
                                                                            {{ $game->team2->team_name }}</div>
                                                                    </div>
                                                                    <div class="team-score">
                                                                        {{ $game->team2_score ?? '-' }}</div>
                                                                </div>

                                                                @if (!$game->isCompleted())
                                                                    <form action="{{ route('games.update', $game) }}"
                                                                        method="POST" class="score-input-form">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="number" name="team1_score"
                                                                            placeholder="{{ $game->team1->team_name }}"
                                                                            min="0" required>
                                                                        <span class="vs-text">-</span>
                                                                        <input type="number" name="team2_score"
                                                                            placeholder="{{ $game->team2->team_name }}"
                                                                            min="0" required>
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm">
                                                                            <i class="bi bi-check2"></i> Update
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="team-matchup">
                                                                <div class="team-row">
                                                                    <div class="team-info">
                                                                        <div class="team-logo">?</div>
                                                                        <div class="team-name text-muted">
                                                                            {{ $game->team1 ? $game->team1->team_name : 'TBD' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="team-score">-</div>
                                                                </div>
                                                                <div class="vs-divider"><span>VS</span></div>
                                                                <div class="team-row">
                                                                    <div class="team-info">
                                                                        <div class="team-logo">?</div>
                                                                        <div class="team-name text-muted">
                                                                            {{ $game->team2 ? $game->team2->team_name : 'TBD' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="team-score">-</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="game-actions">
    @if (!$game->isCompleted() && $game->isReady() && $game->status !== 'in_progress')
        <a href="{{ route('games.prepare', $game->id) }}" class="start-game-btn">
            <i class="bi bi-play-fill"></i> Start Game
        </a>
    @elseif($game->status === 'in_progress')
        {{-- Route to correct sport live game --}}
        @if($game->isVolleyball())
            <a href="{{ route('games.volleyball-live', $game->id) }}" class="start-game-btn" style="background: #FF9800;">
                <i class="bi bi-play-circle"></i> Resume Volleyball
            </a>
        @else
            <a href="{{ route('games.live', $game->id) }}" class="start-game-btn" style="background: #FF9800;">
                <i class="bi bi-play-circle"></i> Resume Basketball
            </a>
        @endif
    @endif

    {{-- Scoresheet button - route to correct sport --}}
    @if($game->isVolleyball())
        <a href="#" class="tally-sheet-btn" onclick="openVolleyballScoresheet({{ $game->id }})">
            <i class="bi bi-clipboard-data"></i> Volleyball Sheet
        </a>
    @else
        <a href="#" class="tally-sheet-btn" onclick="openTallySheet({{ $game->id }})">
            <i class="bi bi-clipboard-data"></i> Basketball Sheet
        </a>
    @endif

    {{-- Box Score button - route to correct sport --}}
    @if($game->isVolleyball())
        <a href="{{ route('games.volleyball-box-score', $game->id) }}" class="box-score-btn">
            <i class="bi bi-table"></i> Volleyball Stats
        </a>
    @else
        <a href="{{ route('games.box-score', $game->id) }}" class="box-score-btn">
            <i class="bi bi-table"></i> Basketball Stats
        </a>
    @endif
</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    @if ($bracket->games->count() == 0)
                                        <div class="empty-games">
                                            <i class="bi bi-calendar-x"></i>
                                            <p>No games scheduled yet.</p>
                                            <p class="text-muted small">Games will appear here once the bracket is
                                                generated.</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="empty-state">
                                <i class="bi bi-diagram-3"></i>
                                <p>Tournament not generated yet. Click "Generate Tournament" to start!</p>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- Update the Create Bracket Modal section -->
    <div class="modal fade" id="createBracketModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content tournament-page">
                <form action="{{ route('brackets.store', $tournament->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-diagram-3"></i>
                            Create New Bracket
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Bracket Name</label>
                            <input type="text" name="name" class="form-control" value="Main Bracket" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bracket Type</label>
                            <select name="type" class="form-select" required id="bracketTypeSelect">
                                <option value="single-elimination">Single Elimination</option>
                                <option value="round-robin">Round Robin</option>
                                <option value="round-robin-playoff">Round Robin + Playoffs</option>
                                <option value="double-elimination" disabled>Double Elimination (Coming Soon)</option>
                            </select>
                        </div>
                        <div class="bracket-type-info">
                            <div id="single-elimination-info" class="bracket-info active">
                                <div class="alert alert-info">
                                    <strong>Single Elimination:</strong> Teams are eliminated after one loss. Winner
                                    advances, loser is out. Requires minimum 8 teams.
                                </div>
                            </div>
                            <div id="round-robin-info" class="bracket-info">
                                <div class="alert alert-success">
                                    <strong>Round Robin:</strong> Every team plays every other team once. Final standings
                                    based on wins/losses and point difference. Requires minimum 3 teams.
                                </div>
                            </div>
                            <div id="round-robin-playoff-info" class="bracket-info">
                                <div class="alert alert-warning">
                                    <strong>Round Robin + Playoffs:</strong> All teams play each other first, then top 4
                                    advance to single elimination playoffs (semifinals, finals, 3rd place game). Requires
                                    minimum 6 teams (even numbers only).
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2"></i>
                            Create Bracket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Team Assignment Modal -->
    <div class="modal fade" id="assignTeamModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content tournament-page">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-people"></i>
                        Add Teams to Tournament
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Info Banner -->
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Select teams to add:</strong> Only compatible {{ $tournament->sport->sports_name }} teams
                        in other tournaments are shown.
                    </div>

                    <!-- Search Box -->
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" id="teamSearchInput"
                                placeholder="Search by team name or coach...">
                        </div>
                    </div>

                    <!-- Selection Header -->
                    <div class="selection-header mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="selection-info">
                                <span class="selected-count">0</span> team(s) selected
                            </div>
                            <div class="selection-actions">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="selectAllCompatible">
                                    <i class="bi bi-check-square"></i>
                                    Select All
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="clearSelection">
                                    <i class="bi bi-x-square"></i>
                                    Clear
                                </button>
                                <button type="button" class="btn btn-primary" id="addSelectedTeams" disabled>
                                    <i class="bi bi-plus-circle"></i>
                                    Add Selected
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="filter-tabs mb-3"
                        style="display: flex; gap: 0.5rem; border-bottom: 2px solid #dee2e6; padding-bottom: 0.5rem;">
                        <button type="button" class="filter-tab active" data-filter="available"
                            style="padding: 0.5rem 1rem; border: none; background: none; cursor: pointer; border-bottom: 3px solid transparent; font-weight: 600; transition: all 0.3s;">
                            <i class="bi bi-check-circle"></i>
                            Available (<span id="availableCount">0</span>)
                        </button>
                        <button type="button" class="filter-tab" data-filter="added"
                            style="padding: 0.5rem 1rem; border: none; background: none; cursor: pointer; border-bottom: 3px solid transparent; font-weight: 600; transition: all 0.3s;">
                            <i class="bi bi-star-fill"></i>
                            Already Added (<span id="addedCount">0</span>)
                        </button>
                    </div>

                    <!-- Teams Grid -->
                    <div class="teams-grid" id="teamsGrid">
                        @php
                            $allTeams = \App\Models\Team::with('sport')->get();
                            $tournamentSport = $tournament->sport_id;
                            $availableTeamsCount = 0;
                            $addedTeamsCount = 0;
                        @endphp

                        @if ($allTeams->count() > 0)
                            @foreach ($allTeams as $team)
                                @php
                                    $isCompatible = $team->sport_id === $tournamentSport;
                                    $isAlreadyAdded = $team->tournament_id == $tournament->id;
                                    $isInOtherTournament =
                                        $team->tournament_id && $team->tournament_id != $tournament->id;

                                    // Only show compatible teams that are either unassigned or assigned to THIS tournament
                                    $shouldShow = $isCompatible && !$isInOtherTournament;

                                    if (!$shouldShow) {
                                        continue;
                                    }

                                    if ($isAlreadyAdded) {
                                        $addedTeamsCount++;
                                    } else {
                                        $availableTeamsCount++;
                                    }
                                @endphp

                                <div class="team-card-wrapper {{ $isAlreadyAdded ? 'added' : 'available' }}"
                                    data-team-name="{{ strtolower($team->team_name ?? '') }}"
                                    data-coach-name="{{ strtolower($team->coach_name ?? '') }}"
                                    data-status="{{ $isAlreadyAdded ? 'added' : 'available' }}">

                                    @if ($isAlreadyAdded)
                                        <!-- Already added to this tournament -->
                                        <div class="team-selection-card assigned">
                                            <div class="team-card-content">
                                                <div class="checkbox-container">
                                                    <i class="bi bi-check-circle-fill text-primary"></i>
                                                </div>
                                                <div class="team-info">
                                                    <h6>{{ $team->team_name }}</h6>
                                                    <div class="team-details">
                                                        <div class="detail-item">
                                                            <i class="bi bi-person"></i>
                                                            <span>{{ $team->coach_name ?? 'No coach' }}</span>
                                                        </div>
                                                        <div class="detail-item">
                                                            <i class="bi bi-trophy"></i>
                                                            <span>{{ $team->sport->sports_name ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="status-badge assigned">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    Added
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Available to add -->
                                        <div class="team-selection-card selectable compatible"
                                            data-team-id="{{ $team->id }}">
                                            <div class="team-card-content">
                                                <div class="checkbox-container">
                                                    <input type="checkbox" class="team-checkbox"
                                                        data-team-id="{{ $team->id }}"
                                                        data-team-name="{{ $team->team_name }}"
                                                        id="team_{{ $team->id }}">
                                                    <label for="team_{{ $team->id }}"
                                                        class="checkbox-label"></label>
                                                </div>
                                                <div class="team-info">
                                                    <h6>{{ $team->team_name }}</h6>
                                                    <div class="team-details">
                                                        <div class="detail-item">
                                                            <i class="bi bi-person"></i>
                                                            <span>{{ $team->coach_name ?? 'No coach' }}</span>
                                                        </div>
                                                        <div class="detail-item">
                                                            <i class="bi bi-trophy"></i>
                                                            <span>{{ $team->sport->sports_name ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="status-badge available">
                                                    <i class="bi bi-plus-circle"></i>
                                                    Available
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            @if ($availableTeamsCount == 0 && $addedTeamsCount == 0)
                                <div class="no-teams-message" style="grid-column: 1/-1;">
                                    <div class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="text-muted mt-3">No compatible
                                            {{ $tournament->sport->sports_name ?? 'Sport' }} teams available.</p>
                                        <small class="text-muted">Teams in other tournaments are hidden.</small>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="no-teams-message" style="grid-column: 1/-1;">
                                <div class="text-center py-5">
                                    <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="text-muted mt-3">No teams found. Create teams first.</p>
                                    <a href="{{ route('teams.index') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle"></i>
                                        Create Teams
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Summary Stats -->
                    <div class="team-summary-stats mt-3 p-3 bg-light rounded">
                        <div class="row text-center g-3">
                            <div class="col-6 col-md-3">
                                <div class="h5 mb-1 text-success" id="statsAvailable">{{ $availableTeamsCount }}</div>
                                <small class="text-muted">Available</small>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="h5 mb-1 text-primary" id="statsAdded">{{ $addedTeamsCount }}</div>
                                <small class="text-muted">Added</small>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="h5 mb-1 text-info" id="statsTotal">
                                    {{ $availableTeamsCount + $addedTeamsCount }}</div>
                                <small class="text-muted">Total Shown</small>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="h5 mb-1 text-muted" id="statsHidden">
                                    {{ $allTeams->count() - $availableTeamsCount - $addedTeamsCount }}</div>
                                <small class="text-muted">In Other Tournaments</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Teams already in other tournaments are hidden
                        </small>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i>
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Script loaded successfully');
        let handlersAttached = false;
        let selectedTeams = new Set();

        // ADD THIS - Set initial filter counts
        const availableCount = document.querySelectorAll('.team-card-wrapper.available').length;
        const addedCount = document.querySelectorAll('.team-card-wrapper.added').length;

        const availableCountEl = document.getElementById('availableCount');
        const addedCountEl = document.getElementById('addedCount');

        if (availableCountEl) availableCountEl.textContent = availableCount;
        if (addedCountEl) addedCountEl.textContent = addedCount;

        // Get modal elements
        const assignModal = document.getElementById('assignTeamModal');
        if (assignModal) {
            console.log('Assignment modal found');
            assignModal.addEventListener('shown.bs.modal', function() {
                console.log('Assignment modal opened');
                if (handlersAttached) {
                    console.log('Handlers already attached, skipping...');
                    return;
                }
                handlersAttached = true;
                console.log('Attaching multiple selection handlers');
                // Initialize multiple selection functionality
                initializeMultipleSelection();
            });

            function initializeMultipleSelection() {
                // Team checkbox handlers
                const teamCheckboxes = document.querySelectorAll('.team-checkbox');
                const selectableCards = document.querySelectorAll('.team-selection-card.selectable');

                // Add click handlers for both checkboxes and cards
                selectableCards.forEach(card => {
                    const checkbox = card.querySelector('.team-checkbox');
                    if (checkbox) {
                        // Card click handler
                        card.addEventListener('click', function(e) {
                            if (e.target.type !== 'checkbox' && !e.target.classList.contains(
                                    'checkbox-label')) {
                                checkbox.checked = !checkbox.checked;
                                handleTeamSelection(checkbox);
                            }
                        });

                        // Checkbox change handler
                        checkbox.addEventListener('change', function() {
                            handleTeamSelection(this);
                        });
                    }
                });

                // Add this to your existing DOMContentLoaded function
                const bracketTypeSelect = document.getElementById('bracketTypeSelect');
                const bracketInfos = document.querySelectorAll('.bracket-info');

                if (bracketTypeSelect) {
                    bracketTypeSelect.addEventListener('change', function() {
                        bracketInfos.forEach(info => info.classList.remove('active'));

                        const selectedType = this.value;
                        const targetInfo = document.getElementById(selectedType + '-info');
                        if (targetInfo) {
                            targetInfo.classList.add('active');
                        }
                    });
                }

                // Bulk action handlers
                const selectAllBtn = document.getElementById('selectAllCompatible');
                const clearSelectionBtn = document.getElementById('clearSelection');
                const addSelectedBtn = document.getElementById('addSelectedTeams');

                if (selectAllBtn) {
                    selectAllBtn.addEventListener('click', selectAllCompatible);
                }
                if (clearSelectionBtn) {
                    clearSelectionBtn.addEventListener('click', clearSelection);
                }
                if (addSelectedBtn) {
                    addSelectedBtn.addEventListener('click', addSelectedTeams);
                }
            }

            function handleTeamSelection(checkbox) {
                const teamId = parseInt(checkbox.getAttribute('data-team-id'));
                const teamName = checkbox.getAttribute('data-team-name');
                const card = checkbox.closest('.team-selection-card');

                if (checkbox.checked) {
                    selectedTeams.add({
                        id: teamId,
                        name: teamName
                    });
                    card.classList.add('selected');
                } else {
                    selectedTeams.delete([...selectedTeams].find(team => team.id === teamId));
                    card.classList.remove('selected');
                }
                updateSelectionUI();
            }

            function selectAllCompatible() {
                const compatibleCheckboxes = document.querySelectorAll(
                    '.team-selection-card.compatible .team-checkbox');
                compatibleCheckboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        checkbox.checked = true;
                        handleTeamSelection(checkbox);
                    }
                });
            }

            function clearSelection() {
                const checkedBoxes = document.querySelectorAll('.team-checkbox:checked');
                checkedBoxes.forEach(checkbox => {
                    checkbox.checked = false;
                    handleTeamSelection(checkbox);
                });
            }

            function updateSelectionUI() {
                const selectedCount = selectedTeams.size;
                const selectedCountElement = document.querySelector('.selected-count');
                const addSelectedBtn = document.getElementById('addSelectedTeams');

                if (selectedCountElement) {
                    selectedCountElement.textContent = selectedCount;
                }
                if (addSelectedBtn) {
                    addSelectedBtn.disabled = selectedCount === 0;
                    if (selectedCount > 0) {
                        addSelectedBtn.innerHTML =
                            `<i class="bi bi-plus-circle"></i> Add ${selectedCount} Team${selectedCount > 1 ? 's' : ''}`;
                    } else {
                        addSelectedBtn.innerHTML = '<i class="bi bi-plus-circle"></i> Add Selected Teams';
                    }
                }
            }

            function addSelectedTeams() {
                if (selectedTeams.size === 0) {
                    return;
                }

                const addBtn = document.getElementById('addSelectedTeams');
                if (addBtn) {
                    addBtn.classList.add('loading');
                    addBtn.disabled = true;
                    addBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding Teams...';
                }

                // Create form with multiple team IDs
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('tournaments.assign-teams', $tournament->id) }}';

                // CSRF token
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                // Add each selected team ID
                selectedTeams.forEach(team => {
                    const teamInput = document.createElement('input');
                    teamInput.type = 'hidden';
                    teamInput.name = 'team_ids[]';
                    teamInput.value = team.id;
                    form.appendChild(teamInput);
                });

                // Submit form
                document.body.appendChild(form);
                form.submit();
            }

                    function openVolleyballScoresheet(gameId) {
                    const url = `/games/${gameId}/volleyball-scoresheet`;
                    
                    const scoresheetWindow = window.open(
                        url,
                        'volleyball-scoresheet',
                        'width=1200,height=900,scrollbars=yes,resizable=yes'
                    );

                    if (scoresheetWindow) {
                        scoresheetWindow.focus();
                    } else {
                        alert('Please allow popups for this site to view the scoresheet.');
                    }
                }

            // Enhanced filter functionality that works with selection
            const filterTabs = document.querySelectorAll('.filter-tab');
            const teamCards = document.querySelectorAll('.team-card-wrapper');

            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.getAttribute('data-filter');

                    teamCards.forEach(card => {
                        const status = card.getAttribute('data-status');
                        if (filter === 'available' && status === 'available') {
                            card.classList.remove('hidden');
                        } else if (filter === 'added' && status === 'added') {
                            card.classList.remove('hidden');
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                });
            });

            function updateTeamCounts() {
                const visibleTeams = document.querySelectorAll('.team-card-wrapper:not(.hidden)');
                const visibleCompatible = document.querySelectorAll(
                    '.team-card-wrapper.compatible:not(.hidden)');
                const visibleAssigned = document.querySelectorAll('.team-card-wrapper.assigned:not(.hidden)');
                const visibleAvailable = document.querySelectorAll(
                    '.team-card-wrapper.compatible:not(.assigned):not(.unavailable):not(.hidden)');

                const totalElement = document.getElementById('totalTeams');
                const compatibleElement = document.getElementById('compatibleTeams');
                const assignedElement = document.getElementById('assignedTeams');
                const availableElement = document.getElementById('availableTeams');

                if (totalElement) totalElement.textContent = visibleTeams.length;
                if (compatibleElement) compatibleElement.textContent = visibleCompatible.length;
                if (assignedElement) assignedElement.textContent = visibleAssigned.length;
                if (availableElement) availableElement.textContent = visibleAvailable.length;
            }

            // Enhanced search functionality
            const searchInput = document.getElementById('teamSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    teamCards.forEach(card => {
                        const teamName = card.getAttribute('data-team-name') || '';
                        const coachName = card.getAttribute('data-coach-name') || '';
                        const sport = card.getAttribute('data-sport') || '';

                        const matchesSearch = teamName.includes(searchTerm) ||
                            coachName.includes(searchTerm) ||
                            sport.includes(searchTerm);

                        // Check if card should be visible based on current filter
                        const activeFilter = document.querySelector('.filter-tab.active')
                            ?.getAttribute('data-filter') || 'all';
                        const isCompatible = card.classList.contains('compatible');
                        const isAssigned = card.classList.contains('assigned');

                        let shouldShowByFilter = true;
                        switch (activeFilter) {
                            case 'compatible':
                                shouldShowByFilter = isCompatible && !isAssigned;
                                break;
                            case 'assigned':
                                shouldShowByFilter = isAssigned;
                                break;
                            case 'all':
                            default:
                                shouldShowByFilter = true;
                                break;
                        }

                        const shouldShow = matchesSearch && shouldShowByFilter;
                        if (shouldShow) {
                            card.classList.remove('hidden');
                            card.style.display = 'block';
                        } else {
                            card.classList.add('hidden');
                            card.style.display = 'none';
                        }
                    });
                    updateTeamCounts();
                });
            }

        } else {
            console.error('Assignment modal not found!');
        }

        // Game filtering functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const gameCards = document.querySelectorAll('.game-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                const filter = this.getAttribute('data-filter');
                // Filter game cards
                gameCards.forEach(card => {
                    const cardStatus = card.getAttribute('data-status');
                    if (filter === 'all' || cardStatus === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

    });

    function openTallySheet(gameId) {
        // Open the basketball scoresheet in a new window
        const url = `/games/${gameId}/basketball-scoresheet`;

        const tallysheeetWindow = window.open(
            url,
            'scoresheet',
            'width=1200,height=900,scrollbars=yes,resizable=yes'
        );

        if (tallysheeetWindow) {
            tallysheeetWindow.focus();
        } else {
            alert('Please allow popups for this site to view the tallysheet.');
        }
    }

    // Drag and Drop Bracket Customizer Functions
    let draggedTeamData = null;
    let placedTeamsData = new Map();

    function toggleBracketCustomizer(bracketId) {
        const customizer = document.getElementById(`bracketCustomizer${bracketId}`);
        if (customizer.style.display === 'none' || customizer.style.display === '') {
            customizer.style.display = 'block';
            initializeBracketCustomizer(bracketId);
        } else {
            customizer.style.display = 'none';
        }
    }

    function initializeBracketCustomizer(bracketId) {
        if (!placedTeamsData.has(bracketId)) {
            placedTeamsData.set(bracketId, new Set());
        }
        setupDragAndDrop(bracketId);
        updateCustomizerProgress(bracketId);
    }

    function setupDragAndDrop(bracketId) {
        const draggableTeams = document.querySelectorAll(`[data-bracket-id="${bracketId}"].draggable-team`);
        draggableTeams.forEach(team => {
            team.addEventListener('dragstart', handleDragStart);
            team.addEventListener('dragend', handleDragEnd);
        });

        const dropSlots = document.querySelectorAll(`[data-bracket-id="${bracketId}"].drop-slot`);
        dropSlots.forEach(slot => {
            slot.addEventListener('dragover', handleDragOver);
            slot.addEventListener('drop', handleDrop);
            slot.addEventListener('dragleave', handleDragLeave);
        });
    }

    function handleDragStart(e) {
        const teamId = e.target.getAttribute('data-team-id');
        const teamName = e.target.querySelector('.draggable-team-name').textContent;
        const teamCoach = e.target.querySelector('.draggable-team-details').textContent;

        draggedTeamData = {
            id: parseInt(teamId),
            name: teamName,
            coach: teamCoach,
            element: e.target
        };
        e.target.classList.add('dragging');
    }

    function handleDragEnd(e) {
        e.target.classList.remove('dragging');
        draggedTeamData = null;
    }

    function handleDragOver(e) {
        e.preventDefault();
        if (e.currentTarget.classList.contains('empty')) {
            e.currentTarget.classList.add('drop-target');
        }
    }

    function handleDragLeave(e) {
        e.currentTarget.classList.remove('drop-target');
    }

    function handleDrop(e) {
        e.preventDefault();
        const slot = e.currentTarget;
        const bracketId = parseInt(slot.getAttribute('data-bracket-id'));
        slot.classList.remove('drop-target');

        if (draggedTeamData && slot.classList.contains('empty')) {
            placeTeamInSlot(slot, draggedTeamData, bracketId);
            placedTeamsData.get(bracketId).add(draggedTeamData.id);
            draggedTeamData.element.style.display = 'none';
            updateCustomizerProgress(bracketId);
        }
    }

    function placeTeamInSlot(slot, teamData, bracketId) {
        slot.classList.remove('empty');
        slot.classList.add('occupied');
        slot.innerHTML = `
<div class="drop-slot-content">
<div class="draggable-team-name" style="font-weight: 600; margin-bottom: 4px;">${teamData.name}</div>
<div class="draggable-team-details" style="font-size: 0.8rem; color: var(--text-muted);">${teamData.coach}</div>
</div>
<button class="remove-team-btn" onclick="removeTeamFromSlot(this, ${teamData.id}, ${bracketId})">
<i class="bi bi-x"></i>
</button>
`;
    }

    function removeTeamFromSlot(button, teamId, bracketId) {
        const slot = button.parentElement;
        slot.classList.remove('occupied');
        slot.classList.add('empty');
        slot.innerHTML = '<div class="drop-slot-content">Drop team here</div>';

        // Re-attach event listeners
        slot.addEventListener('dragover', handleDragOver);
        slot.addEventListener('drop', handleDrop);
        slot.addEventListener('dragleave', handleDragLeave);

        placedTeamsData.get(bracketId).delete(teamId);

        // Show the team back in the pool
        const teamElement = document.querySelector(`[data-team-id="${teamId}"][data-bracket-id="${bracketId}"]`);
        if (teamElement) {
            teamElement.style.display = 'block';
        }
        updateCustomizerProgress(bracketId);
    }

    function updateCustomizerProgress(bracketId) {
        const totalTeams = document.querySelectorAll(`[data-bracket-id="${bracketId}"].draggable-team`).length;
        const placedCount = placedTeamsData.get(bracketId).size;
        const percentage = (placedCount / totalTeams) * 100;

        const progressFill = document.getElementById(`progressFill${bracketId}`);
        const progressText = document.getElementById(`progressText${bracketId}`);
        const generateBtn = document.getElementById(`generateCustomBtn${bracketId}`);

        if (progressFill) progressFill.style.width = `${percentage}%`;
        if (progressText) progressText.textContent = `${placedCount} of ${totalTeams} teams placed`;

        if (generateBtn) {
            if (placedCount === totalTeams) {
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<i class="bi bi-play-circle"></i> Generate Custom Bracket';
            } else {
                generateBtn.disabled = true;
                generateBtn.innerHTML = `<i class="bi bi-play-circle"></i> Need ${totalTeams - placedCount} more teams`;
            }
        }
    }

    function autoFillSlots(bracketId) {
        const emptySlots = document.querySelectorAll(`[data-bracket-id="${bracketId}"].drop-slot.empty`);
        const availableTeams = document.querySelectorAll(`[data-bracket-id="${bracketId}"].draggable-team`);

        let slotIndex = 0;
        availableTeams.forEach(team => {
            if (team.style.display !== 'none' && slotIndex < emptySlots.length) {
                const teamData = {
                    id: parseInt(team.getAttribute('data-team-id')),
                    name: team.querySelector('.draggable-team-name').textContent,
                    coach: team.querySelector('.draggable-team-details').textContent,
                    element: team
                };

                placeTeamInSlot(emptySlots[slotIndex], teamData, bracketId);
                placedTeamsData.get(bracketId).add(teamData.id);
                team.style.display = 'none';
                slotIndex++;
            }
        });
        updateCustomizerProgress(bracketId);
    }

    function resetCustomizer(bracketId) {
        placedTeamsData.set(bracketId, new Set());

        // Reset all slots
        const slots = document.querySelectorAll(`[data-bracket-id="${bracketId}"].drop-slot`);
        slots.forEach(slot => {
            slot.classList.remove('occupied');
            slot.classList.add('empty');
            slot.innerHTML = '<div class="drop-slot-content">Drop team here</div>';

            // Re-attach event listeners
            slot.addEventListener('dragover', handleDragOver);
            slot.addEventListener('drop', handleDrop);
            slot.addEventListener('dragleave', handleDragLeave);
        });

        // Show all teams back in the pool
        const teams = document.querySelectorAll(`[data-bracket-id="${bracketId}"].draggable-team`);
        teams.forEach(team => {
            team.style.display = 'block';
        });
        updateCustomizerProgress(bracketId);
    }

    function generateCustomBracket(bracketId) {
        const placedTeams = placedTeamsData.get(bracketId);
        const totalTeams = document.querySelectorAll(`[data-bracket-id="${bracketId}"].draggable-team`).length;

        if (placedTeams.size !== totalTeams) {
            alert('Please place all teams before generating the bracket.');
            return;
        }

        // Collect matchup data
        const matchups = [];
        const matchupCards = document.querySelectorAll(`#matchupsGrid${bracketId} .matchup-builder-card`);

        matchupCards.forEach((card, index) => {
            const slots = card.querySelectorAll('.drop-slot.occupied');
            if (slots.length === 2) {
                const team1Name = slots[0].querySelector('.draggable-team-name').textContent;
                const team2Name = slots[1].querySelector('.draggable-team-name').textContent;

                let team1Id = null,
                    team2Id = null;

                // Find team IDs by name
                document.querySelectorAll(`[data-bracket-id="${bracketId}"].draggable-team`).forEach(team => {
                    const name = team.querySelector('.draggable-team-name').textContent;
                    const id = parseInt(team.getAttribute('data-team-id'));
                    if (name === team1Name) team1Id = id;
                    if (name === team2Name) team2Id = id;
                });

                if (team1Id && team2Id) {
                    matchups.push({
                        game: index + 1,
                        team1_id: team1Id,
                        team2_id: team2Id
                    });
                }
            }
        });

        // Submit to backend
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('matchups', JSON.stringify(matchups));

        // Add loading state to button
        const generateBtn = document.getElementById(`generateCustomBtn${bracketId}`);
        if (generateBtn) {
            generateBtn.classList.add('loading');
            generateBtn.disabled = true;
            generateBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generating...';
        }

        fetch(`/brackets/${bracketId}/save-custom`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error generating bracket. Please try again.');
                // Reset button state
                if (generateBtn) {
                    generateBtn.classList.remove('loading');
                    generateBtn.disabled = false;
                    generateBtn.innerHTML = '<i class="bi bi-play-circle"></i> Generate Custom Bracket';
                }
            });
    }
</script>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML =
                '{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            document.body.appendChild(alert);
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML =
                '{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            document.body.appendChild(alert);
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        });
        (function() {
            function debounce(fn, wait = 80) {
                let t;
                return (...args) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn(...args), wait);
                };
            }

            function drawBracketConnectors() {
                const container = document.querySelector('.bracket-container');
                if (!container) return;
                const svg = container.querySelector('.bracket-svg');
                if (!svg) return;

                // size svg to container
                const cRect = container.getBoundingClientRect();
                svg.setAttribute('width', cRect.width);
                svg.setAttribute('height', cRect.height);
                svg.innerHTML = '';

                const rounds = Array.from(container.querySelectorAll('.bracket-round'));
                rounds.forEach((roundEl, rIndex) => {
                    const games = Array.from(roundEl.querySelectorAll('.bracket-game'));
                    const nextRound = rounds[rIndex + 1];
                    if (!nextRound) return;
                    const nextGames = Array.from(nextRound.querySelectorAll('.bracket-game'));

                    games.forEach((gameEl, gIndex) => {
                        const targetIndex = Math.floor(gIndex / 2);
                        const targetEl = nextGames[targetIndex];
                        if (!targetEl) return;

                        const gRect = gameEl.getBoundingClientRect();
                        const tRect = targetEl.getBoundingClientRect();
                        const containerRect = container.getBoundingClientRect();

                        // coordinates relative to container
                        const startX = gRect.right - containerRect.left;
                        const startY = gRect.top + gRect.height / 2 - containerRect.top;
                        const endX = tRect.left - containerRect.left;
                        const endY = tRect.top + tRect.height / 2 - containerRect.top;

                        // draw a smooth cubic curve between centers
                        const midX = startX + (endX - startX) / 2;
                        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        const d =
                            `M ${startX} ${startY} C ${midX} ${startY} ${midX} ${endY} ${endX} ${endY}`;
                        path.setAttribute('d', d);
                        path.setAttribute('stroke', '#9e9e9e');
                        path.setAttribute('stroke-width', '2');
                        path.setAttribute('fill', 'none');
                        path.setAttribute('stroke-linecap', 'round');
                        svg.appendChild(path);
                    });
                });
            }

            window.addEventListener('load', drawBracketConnectors);
            window.addEventListener('resize', debounce(drawBracketConnectors, 80));
            // watch for DOM updates inside the bracket container (e.g., when matches are generated)
            const bracketContainer = document.querySelector('.bracket-container');
            if (bracketContainer) {
                const mo = new MutationObserver(debounce(drawBracketConnectors, 80));
                mo.observe(bracketContainer, {
                    childList: true,
                    subtree: true,
                    attributes: true
                });
            }
        })();
    </script>
@endif
