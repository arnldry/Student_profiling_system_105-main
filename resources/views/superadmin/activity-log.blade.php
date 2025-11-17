<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Activity Log</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/logo-ocnhs.png"/>

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css"/>
    <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/responsive.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />
</head>
<body>
    <div class="header">
        @include('layouts.navbar.superadmin.navbar')
    </div>
    <div class="left-side-bar">
        @include('layouts.sidebar.superadmin.sidebar')
    </div>

    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="title pb-20">
                <h2 class="h3 mb-0">Activity Log</h2>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <p class="mb-0"></p>
                </div>

                <div class="pb-20">
                    <div class="table-responsive">
                        <table class="data-table table stripe hover">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Counselor Name</th>
                                <th>Changes Made</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        <span class="badge badge-light">{{ $log->created_at->format('M d, Y') }}</span><br>
                                        <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $log->admin->name ?? 'Unknown' }}</strong><br>
                                        <small class="text-muted">{{ $log->admin->email ?? '' }}</small>
                                    </td>

                                    <td>
                                        <div class="changes-container">
                                            @if(strpos($log->description, 'Changes:') !== false)
                                                @php
                                                    $parts = explode('Changes:', $log->description);
                                                    $intro = $parts[0];
                                                    $changes = isset($parts[1]) ? $parts[1] : '';
                                                    $changeList = array_filter(explode(', ', trim($changes)));
                                                    $modalId = 'changesModal-' . $log->id;
                                                @endphp
                                                <div class="change-intro">{{ $intro }}</div>
                                                @if(count($changeList) > 0)
                                                    <div class="changes-toggle">
                                                        <button class="btn btn-sm btn-link p-0 text-decoration-none" type="button" onclick="toggleChanges(this)">
                                                            <small>Click to View Changes ({{ count($changeList) }}) <i class="dw dw-chevron-down toggle-icon"></i></small>
                                                        </button>
                                                        <div class="changes-content" style="display: none;">

                                                                @foreach($changeList as $change)
                                                                    <div class="change-item">
                                                                        @php
                                                                            $parts = explode(' → ', $change);
                                                                            $oldPart = $parts[0] ?? $change;
                                                                            $newPart = $parts[1] ?? '';
                                                                        @endphp
                                                                        {{ $oldPart }} → <strong>{{ $newPart }}</strong>
                                                                    </div>
                                                                @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                {{ $log->description }}
                                            @endif
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="/vendors/scripts/core.js"></script>
    <script src="/vendors/scripts/script.min.js"></script>
    <script src="/vendors/scripts/process.js"></script>
    <script src="/vendors/scripts/layout-settings.js"></script>
    <script src="/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable Setting js -->
    <script src="/vendors/scripts/datatable-setting.js"></script>

    <script>
        function toggleChanges(button) {
            const content = button.parentElement.querySelector('.changes-content');
            const icon = button.querySelector('.toggle-icon');

            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                content.classList.add('show');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.display = 'none';
                content.classList.remove('show');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Close other expanded changes when clicking elsewhere
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.changes-toggle')) {
                    document.querySelectorAll('.changes-content').forEach(content => {
                        content.style.display = 'none';
                        content.classList.remove('show');
                        const icon = content.parentElement.querySelector('.toggle-icon');
                        if (icon) {
                            icon.style.transform = 'rotate(0deg)';
                        }
                    });
                }
            });
        });
    </script>

    <style>
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .badge-light {
            background-color: #f8f9fa;
            color: #6c757d;
        }


        .changes-container {
            max-width: 400px;
        }

        .change-intro {
            font-size: 0.9rem;
            color: #495057;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .btn-link {
            color: #007bff;
            text-decoration: none;
        }

        .btn-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .changes-toggle {
            position: relative;
        }

        .changes-content {
            margin-top: 0.5rem;
            padding: 0.5rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .change-item {
            font-size: 0.85rem;
            padding: 0.25rem 0;
            border-bottom: 1px solid #e9ecef;
            position: relative;
            padding-left: 1.5rem;
            line-height: 1.4;
        }

        .change-item:before {
            content: "•";
            color: #28a745;
            font-weight: bold;
            position: absolute;
            left: 0;
            top: 0.25rem;
        }

        .change-item:last-child {
            border-bottom: none;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
            font-size: 12px;
        }

        .changes-content.show .toggle-icon {
            transform: rotate(180deg);
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }


        .table td {
            vertical-align: middle;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table td:last-child {
            max-width: 250px;
            min-width: 200px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .changes-professional {
            max-width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .change-professional {
            white-space: normal;
            word-break: break-word;
            hyphens: auto;
        }

        @media (max-width: 768px) {
            .table td:last-child {
                max-width: 150px;
                min-width: 120px;
            }
        }

        .text-muted {
            color: #6c757d !important;
        }
    </style>
</body>
</html>