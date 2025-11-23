<style>
    .skeleton-loader {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 20px;
    }

    .loader-header,
    .loader-body {
        display: flex;
        flex-direction: column;
    }

    .skeleton {
        background-color: #ddd;
        border-radius: 4px;
    }

    .skeleton-text {
        width: 70%;
        height: 20px;
        margin-bottom: 10px;
    }

    .skeleton-close {
        width: 10px;
        height: 10px;
        align-self: flex-end;
    }

    .skeleton-input {
        width: 100%;
        height: 20px;
    }

    .skeleton-textarea {
        width: 100%;
        height: 60px;
    }

    /* Animation to show loading effect */
    .skeleton {
        animation: shimmer 1.5s infinite linear;
    }

    @keyframes shimmer {
        0% {
            background-color: #e0e0e0;
        }

        50% {
            background-color: #c7c7c7;
        }

        100% {
            background-color: #e0e0e0;
        }
    }

    #toast-container {
        .toast-success {
            background-color: #0478ed !important;
        }
    }

    .file-icon > p {
        font-size: medium;
    }

    table > tbody > tr > td {
        text-align: center !important;
    }

    table > thead > tr > th {
        text-align: center !important;
    }

    .modal-xl {
        max-width: 90%;
        /* You can adjust this percentage as needed */
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .modal-content {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-10px);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 10px 10px 0 0 !important;
    }

    .card-body {
        padding: 20px;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 500;
    }

    .card-text {
        font-size: 1rem;
        color: #6c757d;
    }

    .card-link {
        color: #007bff;
        text-decoration: none;
    }

    .side-menu .slide:hover {
        transform: translateX(5px);

    }

    #toast-container .toast {
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* جدول البيانات */
    table.dataTable {
        border-collapse: separate !important;
        border-spacing: 0 10px !important;
        width: 100% !important;
        font-family: 'Cairo';
        background-color: #fff;
    }

    table.dataTable thead th {
        background-color: #f4f6fa !important;
        color: #333 !important;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6 !important;
    }

    table.dataTable tbody td {
        background-color: #fff !important;
        border-bottom: 1px solid #eee !important;
        padding: 12px 16px !important;
        vertical-align: middle !important;
        color: #444;
    }

    table.dataTable tbody tr:hover td {
        background-color: #f9fbff !important;
    }

    /* أزرار التعديل والحذف */
    .table .btn-edit,
    .table .btn-delete {
        border-radius: 50%;
        width: 34px;
        height: 34px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s ease;
        padding: 0;
    }

    .table .btn-edit {
        background-color: #e0f2ff;
        color: #007bff;
    }

    .table .btn-edit:hover {
        background-color: #cfe9ff;
    }

    .table .btn-delete {
        background-color: #ffe0e0;
        color: #dc3545;
    }

    .table .btn-delete:hover {
        background-color: #ffcfcf;
    }

    /* ترقيم الصفحات */
    .dataTables_paginate .pagination {
        margin-top: 20px;
    }

    .dataTables_paginate .page-item {
        margin: 0 3px;
    }

    .dataTables_paginate .page-item .page-link {
        border-radius: 6px !important;
        padding: 6px 12px !important;
        color: #007bff !important;
        border: 1px solid #dee2e6 !important;
        transition: 0.3s;
    }

    .dataTables_paginate .page-item.active .page-link {
        background-color: #007bff !important;
        border-color: #007bff !important;
        color: #fff !important;
        font-weight: bold;
    }

    .dataTables_paginate .page-item .page-link:hover {
        background-color: #dee2e6 !important;
    }

    button {
        border-radius: 20px !important;
        transition: 0.3s;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    a.btn {
        border-radius: 20px !important;
        transition: 0.3s;
    }

    a.btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
