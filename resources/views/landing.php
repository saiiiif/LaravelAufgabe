<div class="splash-container">
    <div class="splash-card">
        <div class="logo">
            <i class="fas fa-boxes"></i>
        </div>
        <h1 class="splash-title">Stock Manager</h1>
        <p class="splash-subtitle">Preparing your inventory workspace...</p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%"></div>
        </div>
        <p class="splash-help">Loading dashboard &amp; syncing storage data. Please wait a moment.</p>
        <button type="button" class="btn btn-primary btn-block mt-3" id="continue-button">Continue to sign in</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const redirect = () => window.location.href = '/login';
        const button = document.getElementById('continue-button');
        button?.addEventListener('click', redirect);
        setTimeout(redirect, 4000);
    });
</script>
