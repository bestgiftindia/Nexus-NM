<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center">
                ©
                <span data-current-year></span>
                {{ config('app.name') }} Made In <span class="fw-semibold">India</span>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="centermodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Center modal</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="mt-0">Overflowing text to show scroll behavior</h5>
                <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas
                    eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                <p class="mb-0">Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                    lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
            </div>
        </div>
    </div>
</div>

<x-account.modal.logout />
