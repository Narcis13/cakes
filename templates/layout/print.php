<?php
/**
 * CakePHP Print Layout - Minimal layout for printable reports
 */
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        SMUP - <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>

    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        @media print {
            body {
                font-size: 9pt;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                padding: 0;
                box-shadow: none;
                max-width: none;
            }

            .table {
                font-size: 8pt;
            }

            .table th,
            .table td {
                padding: 0.2rem 0.4rem;
            }

            .badge {
                border: 1px solid #000;
            }
        }

        @media screen {
            body {
                background-color: #f5f5f5;
                padding: 20px;
                margin: 0;
            }

            .print-container {
                background-color: white;
                width: calc(100vw - 40px);
                max-width: none;
                margin: 0 auto;
                padding: 25px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                overflow-x: auto;
            }
        }

        .print-header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .print-header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .print-header .subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        .filters-section {
            background-color: #f8f9fa;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .filters-section h5 {
            margin: 0 0 5px 0;
            font-size: 0.9rem;
        }

        .filters-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filters-list li {
            font-size: 0.85rem;
        }

        .filters-list strong {
            color: #333;
        }

        .print-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 0.85rem;
            color: #666;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            white-space: nowrap;
        }

        .table th,
        .table td {
            padding: 0.5rem;
            vertical-align: middle;
        }

        @media screen and (min-width: 1200px) {
            .table {
                white-space: normal;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <?= $this->fetch('content') ?>
    </div>

    <script>
        // Auto print on page load (optional - user can use Cmd+P)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
