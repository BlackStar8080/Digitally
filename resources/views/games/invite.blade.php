@extends('layouts.app')

@section('title', 'Generate Game Invite - ' . $game->team1->team_name . ' vs ' . $game->team2->team_name)

@section('content')
<div style="max-width: 800px; margin: 2rem auto; padding: 0 1rem;">
    
    <div style="background: white; border-radius: 16px; padding: 3rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
        
        <h1 style="color: #333; margin-bottom: 0.5rem;">
            <i class="bi bi-link-45deg"></i> Invite Stat-keeper
        </h1>
        
        <p style="color: #666; margin-bottom: 2rem;">
            Share this link or QR code with your stat-keeper to join the game
        </p>

        <!-- Loading State -->
        <div id="loadingState" style="text-align: center; display: none;">
            <div style="display: inline-block; border: 4px solid #f3f3f3; border-top: 4px solid #4CAF50; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin-bottom: 1rem;"></div>
            <p style="color: #999;">Generating invite link...</p>
        </div>

        <!-- Content State -->
        <div id="contentState" style="display: none;">
            
            <!-- Invite Link Section -->
            <div style="background: #f8f9fa; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
                <h3 style="color: #333; margin-bottom: 1rem;">📎 Invite Link</h3>
                
                <div style="display: flex; gap: 0.5rem;">
                    <input 
                        type="text" 
                        id="joinLink" 
                        readonly
                        style="
                            flex: 1;
                            padding: 0.75rem 1rem;
                            border: 2px solid #ddd;
                            border-radius: 8px;
                            font-family: monospace;
                            font-size: 14px;
                        "
                    />
                    <button 
                        onclick="copyToClipboard()"
                        style="
                            padding: 0.75rem 1.5rem;
                            background: #4CAF50;
                            color: white;
                            border: none;
                            border-radius: 8px;
                            cursor: pointer;
                            font-weight: 600;
                            transition: all 0.3s;
                        "
                        onmouseover="this.style.background='#45a049'"
                        onmouseout="this.style.background='#4CAF50'"
                    >
                        <i class="bi bi-clipboard"></i> Copy
                    </button>
                </div>
                
                <p style="color: #999; font-size: 12px; margin-top: 0.5rem;">
                    ⏰ Link expires in 4 hours
                </p>
            </div>

            <!-- QR Code Section -->
            <div style="background: #f8f9fa; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; text-align: center;">
                <h3 style="color: #333; margin-bottom: 1rem;">📱 QR Code</h3>
                
                <div id="qrCode" style="display: inline-block;"></div>
                
                <p style="color: #999; font-size: 12px; margin-top: 1rem;">
                    Scan with phone to join game
                </p>
            </div>

            <!-- Instructions -->
            <div style="background: #e3f2fd; border-left: 4px solid #2196F3; padding: 1rem; border-radius: 8px;">
                <h4 style="color: #1976D2; margin-bottom: 0.5rem;">
                    <i class="bi bi-info-circle"></i> How to Use
                </h4>
                <ol style="color: #555; margin: 0; padding-left: 1.5rem;">
                    <li>Share the link via WhatsApp, SMS, or Email</li>
                    <li>Or show the QR code to the stat-keeper</li>
                    <li>They open the link and log in (or create account)</li>
                    <li>They automatically join as Stat-keeper</li>
                    <li>Come back here and click "Start Game"</li>
                </ol>
            </div>

            <!-- Back Button -->
            <div style="margin-top: 2rem; text-align: center;">
                <a 
                    href="{{ route('games.prepare', $game) }}"
                    style="
                        display: inline-block;
                        padding: 0.75rem 1.5rem;
                        background: #f0f0f0;
                        color: #333;
                        text-decoration: none;
                        border-radius: 8px;
                        font-weight: 600;
                        transition: all 0.3s;
                    "
                    onmouseover="this.style.background='#e0e0e0'"
                    onmouseout="this.style.background='#f0f0f0'"
                >
                    ← Back to Preparation
                </a>
            </div>
        </div>

        <!-- Error State -->
        <div id="errorState" style="display: none;">
            <div style="background: #ffebee; border: 1px solid #ef5350; border-radius: 8px; padding: 1rem; color: #c62828;">
                <i class="bi bi-exclamation-triangle"></i>
                <span id="errorMessage"></span>
            </div>
        </div>

    </div>
</div>

<!-- QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<!-- Spin Animation -->
<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
const gameId = {{ $game->id }};
let inviteData = null;

// Generate invite on page load
document.addEventListener('DOMContentLoaded', function() {
    generateInvite();
});

function generateInvite() {
    document.getElementById('loadingState').style.display = 'block';
    document.getElementById('contentState').style.display = 'none';
    document.getElementById('errorState').style.display = 'none';

    fetch(`/games/${gameId}/generate-invite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            inviteData = data;
            displayInvite(data);
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('contentState').style.display = 'block';
        } else {
            throw new Error(data.error || 'Failed to generate invite');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('errorMessage').textContent = error.message;
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('errorState').style.display = 'block';
    });
}

function displayInvite(data) {
    // Set join link
    document.getElementById('joinLink').value = data.join_url;

    // Generate QR code
    const qrContainer = document.getElementById('qrCode');
    qrContainer.innerHTML = ''; // Clear previous QR
    new QRCode(qrContainer, {
        text: data.join_url,
        width: 250,
        height: 250,
        correctLevel: QRCode.CorrectLevel.H
    });
}

function copyToClipboard() {
    const linkInput = document.getElementById('joinLink');
    linkInput.select();
    document.execCommand('copy');

    // Show feedback
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check"></i> Copied!';
    button.style.background = '#4CAF50';

    setTimeout(() => {
        button.innerHTML = originalText;
        button.style.background = '#4CAF50';
    }, 2000);
}

// Regenerate invite if expired
setInterval(() => {
    if (inviteData && new Date() > new Date(inviteData.expires_at)) {
        generateInvite();
    }
}, 30000); // Check every 30 seconds
</script>
@endsection