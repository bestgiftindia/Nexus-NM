@props(['options' => []])

<button type="{{ $options['type'] ?? 'button' }}" class="btn btn-primary py-2 fw-semibold {{ $options['class'] ?? '' }}"
    data-loading-button data-loader-text="{{ $options['loaderText'] ?? 'Please wait...' }}">
    <span id="{{ $options['loaderId'] ?? 'loaderBtn' }}" class="spinner-border spinner-border-sm me-1 d-none"
        role="status" aria-hidden="true" data-spinner>
    </span>

    <span id="{{ $options['buttonId'] ?? 'saveBtn' }}" data-button-text>
        {{ $options['buttonText'] ?? 'Confirm & Submit' }}
    </span>
</button>


@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('[data-loading-button]').forEach(function(button) {

                button.addEventListener('click', function() {

                    const spinner = this.querySelector('[data-spinner]');
                    const buttonText = this.querySelector('[data-button-text]');
                    const loaderText = this.dataset.loaderText;

                    // Show spinner
                    spinner?.classList.remove('d-none');

                    // Change text
                    if (buttonText) {
                        buttonText.textContent = loaderText;
                    }

                    // Disable after click
                    this.disabled = true;
                });

            });

        });
    </script>
@endpush
