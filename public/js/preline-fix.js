/**
 * Preline.js Error Fix
 * Mengatasi error: Cannot read properties of undefined (reading 'length')
 */
document.addEventListener('DOMContentLoaded', function() {
    // Patch untuk moveOverlayToBodyResizeFn dan autoCloseResizeFn
    const patchPrelineFunctions = function() {
        // Tunggu hingga Preline selesai dimuat
        setTimeout(function() {
            if (window.HSOverlay) {
                // Patch untuk moveOverlayToBodyResizeFn
                const originalMoveOverlayFn = window.HSOverlay.moveOverlayToBodyResizeFn;
                window.HSOverlay.moveOverlayToBodyResizeFn = function() {
                    try {
                        if (window.HSOverlay.collection && window.HSOverlay.collection.length) {
                            originalMoveOverlayFn.apply(this, arguments);
                        }
                    } catch (e) {
                        console.warn('Prevented Preline error in moveOverlayToBodyResizeFn');
                    }
                };
            }

            if (window.HSDropdown) {
                // Patch untuk autoCloseResizeFn
                const originalAutoCloseFn = window.HSDropdown.autoCloseResizeFn;
                window.HSDropdown.autoCloseResizeFn = function() {
                    try {
                        if (window.HSDropdown.collection && window.HSDropdown.collection.length) {
                            originalAutoCloseFn.apply(this, arguments);
                        }
                    } catch (e) {
                        console.warn('Prevented Preline error in autoCloseResizeFn');
                    }
                };
            }
        }, 500); // Tunggu 500ms untuk memastikan Preline sudah dimuat
    };

    // Jalankan patch
    patchPrelineFunctions();

    // Jalankan ulang patch saat navigasi Livewire
    document.addEventListener('livewire:navigated', function() {
        patchPrelineFunctions();
    });
});