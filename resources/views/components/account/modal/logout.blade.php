<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body text-center p-4">

                <!-- Title -->
                <h4 class="mb-2" id="logoutModalLabel">
                    Confirm Logout
                </h4>

                <!-- Message -->
                <p class="text-muted mb-4">
                    Are you sure you want to log out? Your current session will be ended, and you will need to sign in
                    again to access your account.
                </p>

                <!-- Buttons -->
                <div class="d-flex justify-content-center gap-2">

                    <!-- Cancel Button -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <!-- Logout Button -->
                    <a href="{{ route('account.logout') }}" class="btn btn-danger" id="logoutBtn">

                        <!-- Loader -->
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="logoutSpinner" role="status"
                            aria-hidden="true">
                        </span>

                        <!-- Button Text -->
                        <span id="logoutBtnText">
                            Logout
                        </span>

                    </a>

                </div>

            </div>

        </div>
    </div>
</div>


@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const logoutBtn = document.getElementById('logoutBtn');
            const logoutSpinner = document.getElementById('logoutSpinner');
            const logoutBtnText = document.getElementById('logoutBtnText');

            if (!logoutBtn) {
                return;
            }

            logoutBtn.addEventListener('click', function() {

                // Show loader
                if (logoutSpinner) {
                    logoutSpinner.classList.remove('d-none');
                }

                // Change button text
                if (logoutBtnText) {
                    logoutBtnText.textContent = 'Logging out...';
                }

                // Prevent multiple clicks
                this.style.pointerEvents = 'none';

                // Add disabled style
                this.classList.add('disabled');
            });

        });
    </script>
@endpush
