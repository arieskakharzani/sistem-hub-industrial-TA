@props(['id' => 'signature-pad', 'width' => 400, 'height' => 200])

<div class="w-full">
    <div class="border rounded-lg p-4">
        <canvas id="{{ $id }}" class="border border-gray-300 rounded cursor-crosshair bg-white"
            width="{{ $width }}" height="{{ $height }}">
        </canvas>

        <div class="mt-4 flex justify-between items-center">
            <div>
                <button type="button" onclick="clearSignature('{{ $id }}')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                    Hapus Tanda Tangan
                </button>
            </div>
            <div class="text-sm text-gray-500">
                Klik dan geser mouse untuk membuat tanda tangan
            </div>
        </div>

        <input type="hidden" name="signature" id="{{ $id }}-input">
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initSignaturePad('{{ $id }}');
        });

        function initSignaturePad(canvasId) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            let isDrawing = false;
            let lastX = 0;
            let lastY = 0;
            let hasSignature = false;

            // Set canvas background to white
            ctx.fillStyle = '#fff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Drawing settings
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Touch events
            canvas.addEventListener('touchstart', handleTouchStart);
            canvas.addEventListener('touchmove', handleTouchMove);
            canvas.addEventListener('touchend', stopDrawing);

            function startDrawing(e) {
                isDrawing = true;
                hasSignature = true;
                [lastX, lastY] = getCoordinates(e);
            }

            function draw(e) {
                if (!isDrawing) return;

                e.preventDefault();
                const [currentX, currentY] = getCoordinates(e);

                ctx.beginPath();
                ctx.moveTo(lastX, lastY);
                ctx.lineTo(currentX, currentY);
                ctx.stroke();

                [lastX, lastY] = [currentX, currentY];
                updateSignatureInput();
            }

            function stopDrawing() {
                isDrawing = false;
                updateSignatureInput();
            }

            function getCoordinates(e) {
                if (e.type.includes('touch')) {
                    const touch = e.touches[0];
                    const rect = canvas.getBoundingClientRect();
                    return [
                        touch.clientX - rect.left,
                        touch.clientY - rect.top
                    ];
                }
                const rect = canvas.getBoundingClientRect();
                return [
                    e.clientX - rect.left,
                    e.clientY - rect.top
                ];
            }

            function handleTouchStart(e) {
                e.preventDefault();
                startDrawing(e);
            }

            function handleTouchMove(e) {
                e.preventDefault();
                draw(e);
            }

            function updateSignatureInput() {
                const input = document.getElementById(`${canvasId}-input`);
                input.value = hasSignature ? canvas.toDataURL() : '';
            }

            // Add form validation
            const form = canvas.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const input = document.getElementById(`${canvasId}-input`);
                    if (!input.value) {
                        e.preventDefault();
                        alert('Silahkan buat tanda tangan terlebih dahulu.');
                    }
                });
            }
        }

        function clearSignature(canvasId) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#fff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            const input = document.getElementById(`${canvasId}-input`);
            input.value = '';
        }
    </script>
@endpush
