document.addEventListener("DOMContentLoaded", () => {
    var e = document.querySelector('[data-tables="export-data"]'),
        e =
            (e &&
                new DataTable(e, {
                    dom: "<'d-md-flex justify-content-between align-items-center my-2'Bf>rt<'d-md-flex justify-content-between align-items-center mt-2'ip>",
                    responsive: !0,
                    buttons: [
                        {
                            extend: "copy",
                            className: "btn btn-sm btn-secondary",
                        },
                        {
                            extend: "csv",
                            className: "btn btn-sm btn-secondary active",
                        },
                        {
                            extend: "excel",
                            className: "btn btn-sm btn-secondary",
                        },
                        {
                            extend: "print",
                            className: "btn btn-sm btn-secondary active",
                        },
                        {
                            extend: "pdf",
                            className: "btn btn-sm btn-secondary",
                        },
                    ],
                    language: {
                        paginate: {
                            first: '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 7l-5 5l5 5" /><path d="M17 7l-5 5l5 5" /></svg>',
                            previous:
                                '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>',
                            next: '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>',
                            last: '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7l5 5l-5 5" /><path d="M13 7l5 5l-5 5" /></svg>',
                        },
                    },
                }),
            document.querySelector('[data-tables="export-data-dropdown"]'));
    e &&
        new DataTable(e, {
            processing: true,
            serverSide: true,
            ajax: {
                url: e.dataset.url,
                type: "GET",
            },

            columns: columns ?? [],

            order: [[4, "desc"]],

            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100],
            ],
            dom: "<'d-md-flex justify-content-between align-items-center my-2'<'dropdown'B>f>rt<'d-md-flex justify-content-between align-items-center mt-2'ip>",
            responsive: true,
            buttons: [
                {
                    extend: "collection",
                    text: '<svg  xmlns="http://www.w3.org/2000/svg"  width="14"  height="14"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="me-1 align-baseline"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg> Export',
                    className: "btn btn-sm btn-secondary dropdown-toggle",
                    autoClose: !0,
                    buttons: [
                        { extend: "copy", text: "Copy" },
                        { extend: "csv", text: "CSV" },
                        { extend: "excel", text: "Excel" },
                        { extend: "print", text: "Print" },
                        { extend: "pdf", text: "PDF" },
                    ],
                },
            ],
            language: {
                paginate: {
                    first: '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 7l-5 5l5 5" /><path d="M17 7l-5 5l5 5" /></svg>',
                    previous:
                        '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>',
                    next: '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>',
                    last: '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7l5 5l-5 5" /><path d="M13 7l5 5l-5 5" /></svg>',
                },
            },
            createdRow: function () {
                lucide.createIcons();
            },
            drawCallback: function () {
                lucide.createIcons();
            },
        });
});

$(document).on("change", ".status-switch", function () {
    let id = $(this).data("id");
    let status = $(this).is(":checked") ? 1 : 0;

    $.ajax({
        url: typeof publishUrl != "undefined" ? publishUrl : "",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            id: id,
            is_publish: status,
        },
        success: function (response) {
            console.log(response.message);
            $('[data-tables="export-data-dropdown"]')
                        .DataTable()
                        .ajax.reload(null, false);
        },
        error: function () {
            alert("Something went wrong.");
        },
    });
});


$(document).on('click', '.delete-record', function () {

    let url = $(this).data('url');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover this role!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },

                success: function (response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('[data-tables="export-data-dropdown"]')
                        .DataTable()
                        .ajax.reload(null, false);
                },

                error: function (xhr) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message ?? 'Something went wrong.'
                    });

                }
            });

        }

    });

});