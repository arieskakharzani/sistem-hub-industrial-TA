<!-- Footer Component -->
<div class="footer" id="footer">
    {{ $footerText ?? 'Dokumen ini dikeluarkan secara resmi oleh Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo.' }}
    @if (isset($approvalDate))
        pada {{ $approvalDate }},
        {{ $approvalTime ?? \Carbon\Carbon::now()->format('H:i') }} WIB.
    @endif
</div>

<style>
    /* Footer - Fixed position untuk muncul di setiap halaman PDF */
    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: white;
        border-top: 1px solid #ccc;
        padding: 15px 30px;
        font-size: 11px;
        color: #666;
        font-style: italic;
        line-height: 1.4;
        z-index: 1000;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        min-height: 60px;
    }

    /* Print styles */
    @media print {
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #ccc;
            background: white;
            font-size: 10px;
            padding: 15px 20px;
            margin: 0;
            z-index: 1000;
        }
    }
</style>

<script>
    // Memastikan footer selalu terlihat di setiap halaman
    function ensureFooterVisible() {
        const footer = document.getElementById('footer');
        if (footer) {
            footer.style.display = 'block';
            footer.style.visibility = 'visible';
            footer.style.opacity = '1';
        }
    }

    // Panggil fungsi setelah halaman load
    window.addEventListener('load', function() {
        setTimeout(ensureFooterVisible, 100);
    });

    // Panggil lagi saat scroll atau resize
    window.addEventListener('scroll', ensureFooterVisible);
    window.addEventListener('resize', ensureFooterVisible);
</script>
