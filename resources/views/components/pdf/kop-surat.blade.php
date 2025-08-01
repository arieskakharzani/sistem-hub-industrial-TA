<!-- Kop Surat Component -->
<div class="kop-surat">
    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ base_path('public/img/logo_bungo.png') }}" alt="Logo Kabupaten Bungo">
            </td>
            <td class="text-cell">
                <div class="nama-dinas">DINAS TENAGA KERJA DAN TRANSMIGRASI KABUPATEN BUNGO</div>
                <div class="alamat-dinas">Jl. Damar, No. 831, Kel. Pasir Putih, Kec. Rimbo Tengah Kabupaten</div>
                <div class="alamat-dinas">Bungo, Jambi 37211</div>
                <div class="kontak-dinas">Telepon: (0747) 21013 | Email: nakertrans@bungokab.go.id</div>
            </td>
        </tr>
    </table>
</div>

<style>
    /* Kop Surat - Menggunakan table untuk layout yang lebih stabil */
    .kop-surat {
        width: 100%;
        margin-bottom: 10px;
        border-bottom: 3px solid #000;
        padding-bottom: 8px;
        display: block !important;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10;
    }

    .kop-table {
        width: 100%;
        border-collapse: collapse;
        display: table !important;
    }

    .kop-table tbody {
        display: table-row-group !important;
    }

    .kop-table tr {
        display: table-row !important;
    }

    .kop-table td {
        vertical-align: top;
        padding: 0;
        display: table-cell !important;
    }

    .logo-cell {
        width: 100px;
        padding-right: 15px;
        display: table-cell !important;
    }

    .logo-cell img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        display: block;
    }

    .text-cell {
        width: auto;
        padding-left: 10px;
        display: table-cell !important;
    }

    .nama-dinas {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 8px;
        text-transform: uppercase;
        color: #000;
        line-height: 1.2;
        text-align: center;
    }

    .alamat-dinas {
        font-size: 12px;
        margin-bottom: 3px;
        color: #000;
        line-height: 1.3;
        text-align: center;
    }

    .kontak-dinas {
        font-size: 11px;
        color: #000;
        margin-top: 5px;
        line-height: 1.3;
        text-align: center;
    }

    /* Responsive adjustments - hanya untuk mobile yang sangat kecil */
    @media screen and (max-width: 480px) {

        .kop-table,
        .kop-table tbody,
        .kop-table tr,
        .kop-table td {
            display: block;
            width: 100%;
        }

        .logo-cell {
            text-align: center;
            padding: 0 0 10px 0;
        }

        .text-cell {
            padding: 0;
            text-align: center;
        }
    }

    /* Print styles */
    @media print {
        .kop-surat {
            page-break-after: avoid;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .kop-table {
            display: table !important;
        }

        .kop-table tbody {
            display: table-row-group !important;
        }

        .kop-table tr {
            display: table-row !important;
        }

        .kop-table td {
            display: table-cell !important;
        }
    }
</style>
