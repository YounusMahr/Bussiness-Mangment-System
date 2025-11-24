<!-- Print Styles -->
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }
        
        body {
            margin: 0;
            padding: 0;
            background: white;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        body > * {
            visibility: hidden;
        }
        
        .print-container {
            visibility: visible;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 3rem 2rem;
            box-sizing: border-box;
        }
        
        .print-container * {
            visibility: visible;
        }
        
        .no-print, .no-print * {
            display: none !important;
            visibility: hidden !important;
        }
        
        .print-section {
            position: relative;
            width: 100%;
            margin-bottom: 0;
            border: 2px solid #000;
            background: white;
            page-break-inside: avoid;
            box-sizing: border-box;
        }
        
        .print-section .print-table-header {
            display: block !important;
            padding: 2rem 2rem 1rem 2rem;
            border-bottom: 2px solid #000;
        }
        
        .print-section .print-table-header h2 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #000;
        }
        
        .print-section .print-table-container {
            padding: 0 2rem 2rem 2rem;
        }
        
        #{{ $tableId }} {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px;
            margin-top: 1rem;
            color: #000;
        }
        
        #{{ $tableId }} thead {
            background-color: #2d2d2d !important;
            color: white !important;
        }
        
        #{{ $tableId }} th {
            border: 1.5px solid #000;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            background-color: #2d2d2d !important;
            color: white !important;
        }
        
        #{{ $tableId }} td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: left;
            font-size: 9px;
            color: #000;
        }
        
        #{{ $tableId }} tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        
        .bg-gradient-to-r, .bg-gradient-to-br {
            background: none !important;
            height: auto !important;
        }
        
        .rounded-2xl {
            border-radius: 0 !important;
        }
        
        .shadow-soft-xl {
            box-shadow: none !important;
        }
        
        img, .fas, .fa {
            display: none;
        }
        
        span {
            color: #000 !important;
            background: white !important;
            border: 1px solid #000 !important;
            padding: 0.25rem 0.5rem !important;
            font-size: 8px !important;
        }
        
        * {
            color: #000 !important;
        }
        
        .print-section {
            page-break-inside: avoid;
        }
        
        .overflow-x-auto {
            overflow: visible !important;
        }
    }
</style>

