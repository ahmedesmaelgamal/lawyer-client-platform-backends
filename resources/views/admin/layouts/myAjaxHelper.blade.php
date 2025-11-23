<script>
    var loader = `
			<div id="skeletonLoader" class="skeleton-loader">
    <div class="loader-header">
        <div class="skeleton skeleton-text"></div>
    </div>
    <div class="loader-body">
        <div class="skeleton skeleton-textarea"></div>
    </div>

</div>
        `;


    // Show Data Using YAJRA
    async function showData(routeOfShow, columns) {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: routeOfShow,
            columns: columns,

            order: [
                [0, "DESC"]
            ],
            columnDefs: [{
                targets: 0,
                checkboxes: true,
                render: function(data, type, row, meta) {
                    // Check if column name is 'id', if so don't render checkbox
                    if (meta.settings.aoColumns[meta.col].sName === 'id') {
                        return data; // Return original data
                    }
                    return '<input type="checkbox" class="select-all-checkbox">';
                }
            }],
            "language": {
                "sProcessing": "{{ trns('processing...') }}",
                "sLengthMenu": "{{ trns('show') }} _MENU_ {{ trns('records') }}",
                "sZeroRecords": "{{ trns('no_records_found') }}",
                "sInfo": "{{ trns('showing') }} _START_ {{ trns('to') }} _END_ {{ trns('of') }} _TOTAL_ {{ trns('records') }}",
                "sInfoEmpty": "{{ trns('showing') }} 0 {{ trns('to') }} 0 {{ trns('of') }} 0 {{ trns('records') }}",
                "sInfoFiltered": "({{ trns('filtered_from') }} _MAX_ {{ trns('total_records') }})",
                "sSearch": "{{ trns('search') }} :    ",
                "oPaginate": {
                    "sPrevious": "{{ trns('previous') }}",
                    "sNext": "{{ trns('next') }}",
                },
                buttons: {
                    copyTitle: '{{ trns('copied') }} <i class="fa fa-check-circle text-success"></i>',
                    copySuccess: {
                        1: "{{ trns('copied') }} 1 {{ trns('row') }}",
                        _: "{{ trns('copied') }} %d {{ trns('rows') }}"
                    },
                }
            },

            // dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    text: "{{ trns('copy') }}",
                    className: 'btn-primary'
                },
                {
                    extend: 'print',
                    text: '{{ trns('print') }}',
                    className: 'btn-primary'
                },
                {
                    extend: 'excel',
                    text: '{{ trns('excel') }}',
                    className: 'btn-primary'
                },
                {
                    extend: 'pdf',
                    text: '{{ trns('pdf') }}',
                    className: 'btn-primary'
                },
                {
                    extend: 'colvis',
                    text: '{{ trns('column_visibility') }}',
                    className: 'btn-primary'
                },
            ]
        });
    }

    function deleteScript(routeTemplate) {
        $(document).ready(function() {
            // Configure modal event listeners
            $('#delete_modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var title = button.data('title');
                var modal = $(this);
                modal.find('.modal-body #delete_id').val(id);
                modal.find('.modal-body #title').text(title);
            });

            $(document).on('click', '#delete_btn', function() {
                var id = $("#delete_id").val();
                var routeOfDelete = routeTemplate.replace(':id', id);

                $.ajax({
                    type: 'DELETE',
                    url: routeOfDelete,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(data) {
                        $("#dismiss_delete_modal")[0].click();
                        if (data.status === 200) {
                            $('#dataTable').DataTable().ajax.reload();
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            });
        });
    }




    // show Add Modal
    function showAddModal(routeOfShow) {
        $(document).on('click', '.addBtn', function() {
            $('#modal-body').html(loader)
            $('#editOrCreate').modal('show')
            setTimeout(function() {
                $('#modal-body').load(routeOfShow)
            }, 250)
        });
    }

    function addScript() {
        $(document).on('submit', 'Form#addForm', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var url = $('#addForm').attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#addButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">{{ trns('loading...') }}</span>'
                    ).attr('disabled', true);
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('#dataTable').DataTable().ajax.reload();
                        toastr.success('{{ trns('added_successfully') }}');
                    } else if (data.status == 405) {
                        toastr.error(data.mymessage);
                    } else
                        toastr.error('{{ trns('something_went_wrong') }}');
                    $('#addButton').html(`{{ trns('add') }}`).attr('disabled', false);
                    $('#editOrCreate').modal('hide')
                },
                error: function(data) {
                    if (data.status === 500) {
                        toastr.error('');
                    } else if (data.status === 422) {
                        var errors = $.parseJSON(data.responseText);
                        $.each(errors, function(key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function(key, value) {
                                    toastr.error(value, '{{ trns('error') }}');
                                });
                            }
                        });
                    } else
                        toastr.error('{{ trns('something_went_wrong') }}');
                    $('#addButton').html(`اضافة`).attr('disabled', false);
                }, //end error method

                cache: false,
                contentType: false,
                processData: false
            });
        });
    }

    function showEditModal(routeOfEdit) {
        $(document).on('click', '.editBtn', function() {
            var id = $(this).data('id')
            var url = routeOfEdit;
            url = url.replace(':id', id)
            $('#modal-body').html(loader)
            $('#editOrCreate').modal('show')

            setTimeout(function() {
                $('#modal-body').load(url)
            }, 500)
        })
    }

    function showUpdateProfileImage(routeOfEdit) {
        $(document).on('click', '.updateProfileImageBtn', function() {
            var id = $(this).data('id')
            var url = routeOfEdit;
            url = url.replace(':id', id)
            $('#modal-body').html(loader)
            $('#updateProfileImage').modal('show')

            setTimeout(function() {
                $('#modal-body').load(url)
            }, 500)
        })
    }



    function editScript() {
        $(document).on('submit', 'Form#updateForm', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var url = $('#updateForm').attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#updateButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">{{ trns('loading...') }}</span>'
                    ).attr('disabled', true);
                },
                success: function(data) {
                    $('#updateButton').html(`{{ trns('update') }}`).attr('disabled', false);
                    if (data.status == 200) {
                        toastr.success('{{ trns('updated_successfully') }}');
                    } else
                        toastr.error('{{ trns('something_went_wrong') }}');

                    $('#editOrCreate').modal('hide');
                    if (data.redirect) {
                        setTimeout(function() {
                            window.location.href = data.redirect;
                        }, 1000);
                    } else {
                        $('#dataTable').DataTable().ajax.reload();
                    }
                },
                error: function(data) {
                    if (data.status === 500) {
                        toastr.error('{{ trns('something_went_wrong') }}');
                    } else if (data.status === 422) {
                        var errors = $.parseJSON(data.responseText);
                        $.each(errors, function(key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function(key, value) {
                                    toastr.error(value, '{{ trns('error') }}');
                                });
                            }
                        });
                    } else
                        toastr.error('{{ trns('something_went_wrong') }}');
                    $('#updateButton').html(`{{ trns('update') }}`).attr('disabled', false);
                }, //end error method

                cache: false,
                contentType: false,
                processData: false
            });
        });
    }

    function deleteSelected(route) {
        $(document).ready(function() {
            $('#bulk-delete').prop('disabled', true);

            $('#select-all').on('click', function() {
                const isChecked = $(this).is(':checked');
                $('.delete-checkbox').prop('checked', isChecked);
                toggleBulkDeleteButton();
            });

            $(document).on('change', '.delete-checkbox', function() {
                toggleBulkDeleteButton();
            });

            $('#bulk-delete').on('click', function() {
                const selected = $('.delete-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selected.length > 0) {
                    $('#deleteConfirmModal').modal('show');

                    $('#confirm-delete-btn').off('click').on('click', function() {
                        $.ajax({
                            url: route,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selected
                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    toastr.success(
                                        '{{ trns('deleted_successfully') }}');
                                    $('#select-all').prop('checked', false);
                                    $('.delete-checkbox').prop('checked', false);
                                    $('#dataTable').DataTable().ajax.reload();
                                } else {
                                    toastr.error(
                                        '{{ trns('something_went_wrong') }}');
                                }
                                $('#deleteConfirmModal').modal('hide');
                                toggleBulkDeleteButton();
                            },
                            error: function() {
                                toastr.error('{{ trns('something_went_wrong') }}');
                                $('#deleteConfirmModal').modal('hide');
                                toggleBulkDeleteButton();
                            }
                        });
                    });
                }
            });

            function toggleBulkDeleteButton() {
                const anyChecked = $('.delete-checkbox:checked').length > 0;
                $('#bulk-delete').prop('disabled', !anyChecked);
            }
        });
    }

    function updateColumnSelected(route) {
        $(document).ready(function() {
            $('#bulk-update').prop('disabled', true);

            $('#select-all').on('click', function() {
                const isChecked = $(this).is(':checked');
                $('.delete-checkbox').prop('checked', isChecked);
                toggleBulkUpdateButton();
            });

            $(document).on('change', '.delete-checkbox', function() {
                toggleBulkUpdateButton();
            });

            $('#bulk-update').on('click', function() {
                const selected = $('.delete-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selected.length > 0) {
                    $('#updateConfirmModal').modal('show');

                    // Handle update confirmation
                    $('#confirm-update-btn').off('click').on('click', function() {
                        $.ajax({
                            url: route,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selected
                            },
                            success: function(data) {
                                if (data.status === 200) {
                                    toastr.success(
                                        '{{ trns('updated_successfully') }}');
                                    $('#select-all').prop('checked', false);
                                    $('.delete-checkbox').prop('checked', false);
                                    $('#dataTable').DataTable().ajax.reload();
                                } else {
                                    toastr.error(
                                        '{{ trns('something_went_wrong') }}');
                                }
                                $('#updateConfirmModal').modal('hide');
                                toggleBulkUpdateButton();
                            },
                            error: function(xhr) {
                                toastr.error('{{ trns('something_went_wrong') }}');
                                $('#updateConfirmModal').modal('hide');
                                toggleBulkUpdateButton();
                            }
                        });
                    });
                } else {
                    toastr.error('{{ trns('please_select_first') }}');
                }
            });

            function toggleBulkUpdateButton() {
                const anyChecked = $('.delete-checkbox:checked').length > 0;
                $('#bulk-update').prop('disabled', !anyChecked);
            }
        });
    }

    // for status
    function updateStatus(route) {

        $(document).on('click', '.statusBtnOne', function() {
            let ids = [];
            ids.push($(this).data('id'));
            var val = $(this).is(':checked') ? 'active' : 'inactive';

            $.ajax({
                type: 'POST',
                url: route,
                data: {
                    "_token": "{{ csrf_token() }}",
                    'ids': ids,
                },
                success: function(data) {
                    if (data.status === 200) {
                        $('#dataTable').DataTable().ajax.reload();
                        if (val === 'active') {
                            toastr.success('Success', "{{ trns('active') }}");
                        } else {
                            toastr.warning('Success', "{{ trns('inactive') }}");
                        }
                    } else {
                        toastr.error('Error', "{{ trns('something_went_wrong') }}");
                    }
                },
                error: function() {
                    toastr.error('Error', "{{ trns('something_went_wrong') }}");
                }
            });
        });
    }




  
</script>
