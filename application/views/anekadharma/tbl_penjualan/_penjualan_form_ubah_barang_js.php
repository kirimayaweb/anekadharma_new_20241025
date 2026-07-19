<style>
.penjualan-ubah-swal-process.swal2-popup {
    border-radius: 16px;
    padding: 1.75rem 1.5rem 1.5rem;
}
.penjualan-ubah-process-wrap {
    text-align: center;
    padding: 0.25rem 0 0.5rem;
}
.penjualan-ubah-spinner-ring {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: 4px solid rgba(0, 123, 255, 0.15);
    border-top-color: #007bff;
    animation: penjualanUbahSpin 0.9s linear infinite;
    margin: 0 auto 1rem;
}
.penjualan-ubah-pulse-dots span {
    display: inline-block;
    width: 8px;
    height: 8px;
    margin: 0 4px;
    border-radius: 50%;
    background: #007bff;
    animation: penjualanUbahPulse 1.2s ease-in-out infinite;
}
.penjualan-ubah-pulse-dots span:nth-child(2) { animation-delay: 0.15s; }
.penjualan-ubah-pulse-dots span:nth-child(3) { animation-delay: 0.3s; }
.penjualan-ubah-progress-track {
    height: 6px;
    background: rgba(0, 123, 255, 0.12);
    border-radius: 999px;
    overflow: hidden;
    margin-top: 1rem;
}
.penjualan-ubah-progress-bar {
    height: 100%;
    width: 35%;
    background: linear-gradient(90deg, #007bff, #17a2b8, #007bff);
    background-size: 200% 100%;
    border-radius: 999px;
    animation: penjualanUbahProgress 1.4s ease-in-out infinite;
}
.penjualan-ubah-swal-success.swal2-popup {
    border-radius: 16px;
}
@keyframes penjualanUbahSpin {
    to { transform: rotate(360deg); }
}
@keyframes penjualanUbahPulse {
    0%, 80%, 100% { opacity: 0.35; transform: scale(0.85); }
    40% { opacity: 1; transform: scale(1); }
}
@keyframes penjualanUbahProgress {
    0% { transform: translateX(-120%); background-position: 0% 50%; }
    100% { transform: translateX(320%); background-position: 100% 50%; }
}
</style>
<script>
(function() {
    function parseHargaInputPenjualan(raw) {
        var s = String(raw || '').replace(/\s/g, '');
        if (s.indexOf(',') !== -1 && s.indexOf('.') !== -1) {
            s = s.replace(/\./g, '').replace(',', '.');
        } else if (s.indexOf(',') !== -1) {
            s = s.replace(',', '.');
        }
        var n = parseFloat(s);
        return isNaN(n) ? 0 : n;
    }

    function isRekapUbahForm(form) {
        var inp = form.querySelector('input[name="redirect_rekap"]');
        return !!(inp && String(inp.value) === '1');
    }

    function closeFormModal(form) {
        var modal = form.querySelector('.modal');
        if (!modal) {
            modal = form.closest('.modal');
        }
        if (modal && window.jQuery && typeof jQuery(modal).modal === 'function') {
            jQuery(modal).modal('hide');
        }
    }

    function htmlProcessUbahBarang() {
        return '<div class="penjualan-ubah-process-wrap">'
            + '<div class="penjualan-ubah-spinner-ring"></div>'
            + '<div class="penjualan-ubah-pulse-dots"><span></span><span></span><span></span></div>'
            + '<p class="mt-3 mb-1"><strong>Memproses perubahan data...</strong></p>'
            + '<p class="text-muted small mb-0">Menyinkronkan penjualan dan persediaan, mohon tunggu.</p>'
            + '<div class="penjualan-ubah-progress-track"><div class="penjualan-ubah-progress-bar"></div></div>'
            + '</div>';
    }

    function showProcessUbahBarangSwal() {
        Swal.fire({
            title: 'Sedang Diproses',
            html: htmlProcessUbahBarang(),
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            customClass: {
                popup: 'penjualan-ubah-swal-process'
            },
            didOpen: function() {
                Swal.showLoading();
            }
        });
    }

    function showErrorUbahBarangSwal(message) {
        var teks = String(message || 'Terjadi kesalahan saat memperbarui data.');
        Swal.fire({
            icon: 'error',
            title: 'Gagal Memperbarui',
            html: '<div style="text-align:left;max-height:280px;overflow:auto;font-size:14px;">'
                + teks.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br/>')
                + '</div>',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc3545',
            width: 620
        });
    }

    function parseJsonResponseUbahBarang(text) {
        if (!text) {
            return null;
        }
        text = String(text).replace(/^\uFEFF/, '').trim();
        try {
            return JSON.parse(text);
        } catch (err) {
            return null;
        }
    }

    function extractAjaxErrorMessageUbahBarang(xhr, textStatus, errorThrown) {
        var pesan = 'Terjadi kesalahan saat memproses data.';
        var detail = [];

        if (textStatus) {
            detail.push('Status: ' + textStatus);
        }
        if (errorThrown) {
            detail.push('Error: ' + errorThrown);
        }
        if (xhr && xhr.status) {
            detail.push('HTTP: ' + xhr.status);
        }

        if (xhr && xhr.responseText) {
            var parsed = parseJsonResponseUbahBarang(xhr.responseText);
            if (parsed && parsed.message) {
                return parsed.message;
            }

            var raw = String(xhr.responseText).replace(/^\uFEFF/, '').trim();
            if (raw.indexOf('A PHP Error was encountered') !== -1) {
                var msgMatch = raw.match(/Message:<\/p>\s*<p[^>]*>([^<]+)/i);
                var fileMatch = raw.match(/Filename:<\/p>\s*<p[^>]*>([^<]+)/i);
                var lineMatch = raw.match(/Line Number:<\/p>\s*<p[^>]*>([^<]+)/i);
                if (msgMatch && msgMatch[1]) {
                    pesan = 'PHP Error: ' + msgMatch[1].trim();
                    if (fileMatch && fileMatch[1]) {
                        pesan += ' (file: ' + fileMatch[1].trim();
                        if (lineMatch && lineMatch[1]) {
                            pesan += ', line: ' + lineMatch[1].trim();
                        }
                        pesan += ')';
                    }
                    return pesan;
                }
            }

            var plain = raw.replace(/<script[\s\S]*?<\/script>/gi, ' ')
                .replace(/<style[\s\S]*?<\/style>/gi, ' ')
                .replace(/<[^>]+>/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
            if (plain.length > 0) {
                detail.push('Respon server: ' + plain.substring(0, 500));
            }
        }

        if (detail.length) {
            pesan += '\n' + detail.join('\n');
        }
        return pesan;
    }

    function handleAjaxSuccessUbahBarang(data) {
        if (!data || typeof data !== 'object') {
            showErrorUbahBarangSwal('Respon server tidak valid (bukan JSON).');
            return;
        }
        if (data.ok) {
            if (data.redirect_url) {
                window.location.href = data.redirect_url;
                return;
            }
            Swal.close();
            Swal.fire({
                icon: 'success',
                title: 'Update Selesai',
                html: data.message || 'Proses update selesai.',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                customClass: { popup: 'penjualan-ubah-swal-success' }
            });
            return;
        }
        showErrorUbahBarangSwal(data.message || 'Gagal memperbarui data penjualan.');
    }

    function submitRekapUbahFormAjax(form) {
        showProcessUbahBarangSwal();

        var actionUrl = form.getAttribute('action') || form.action;
        if (!actionUrl) {
            showErrorUbahBarangSwal('URL simpan tidak ditemukan.');
            return;
        }

        if (window.jQuery) {
            var $form = jQuery(form);
            var formData = new FormData(form);
            formData.set('ajax', '1');
            if (!formData.get('redirect_rekap')) {
                formData.set('redirect_rekap', '1');
            }

            jQuery.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'text',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json, text/plain, */*'
                }
            }).done(function(responseText) {
                var data = parseJsonResponseUbahBarang(responseText);
                if (!data) {
                    showErrorUbahBarangSwal('Respon server tidak valid (bukan JSON).\n'
                        + 'Cuplikan: ' + String(responseText || '').substring(0, 400));
                    return;
                }
                handleAjaxSuccessUbahBarang(data);
            }).fail(function(xhr, textStatus, errorThrown) {
                showErrorUbahBarangSwal(extractAjaxErrorMessageUbahBarang(xhr, textStatus, errorThrown));
            });
            return;
        }

        var formData = new FormData(form);
        formData.set('ajax', '1');

        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(function(response) {
                return response.text().then(function(text) {
                    var data = null;
                    try {
                        data = JSON.parse(text);
                    } catch (err) {
                        data = null;
                    }
                    if (!data) {
                        throw new Error('Respon server tidak valid.');
                    }
                    return data;
                });
            })
            .then(function(data) {
                handleAjaxSuccessUbahBarang(data);
            })
            .catch(function(err) {
                showErrorUbahBarangSwal(err && err.message ? err.message : 'Terjadi kesalahan jaringan.');
            });
    }

    function proceedRekapUbahSubmit(form) {
        closeFormModal(form);
        setTimeout(function() {
            submitRekapUbahFormAjax(form);
        }, 280);
    }

    function handleRekapFormSubmit(form, e) {
        e.preventDefault();

        var hargaInput = form.querySelector('input[name="harga_satuan"]');
        if (!hargaInput) {
            proceedRekapUbahSubmit(form);
            return;
        }

        var hargaAwal = parseFloat(hargaInput.getAttribute('data-harga-awal') || '0');
        var hargaBaru = parseHargaInputPenjualan(hargaInput.value);
        var hargaAwalTampil = hargaInput.getAttribute('data-harga-awal-tampil') || hargaInput.value;

        if (Math.abs(hargaBaru - hargaAwal) < 0.01) {
            proceedRekapUbahSubmit(form);
            return;
        }

        Swal.fire({
            title: 'Konfirmasi',
            html: 'Anda akan merubah harga satuan?<br/>Jika Ya, data persediaan akan ada perubahan daftar barang baru yang namanya sama tetapi dengan harga satuan yang berbeda.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true,
            focusCancel: true
        }).then(function(result) {
            if (result.isConfirmed) {
                var konfirm = form.querySelector('input[name="konfirmasi_ubah_harga"]');
                if (!konfirm) {
                    konfirm = document.createElement('input');
                    konfirm.type = 'hidden';
                    konfirm.name = 'konfirmasi_ubah_harga';
                    form.appendChild(konfirm);
                }
                konfirm.value = '1';
                proceedRekapUbahSubmit(form);
            } else {
                hargaInput.value = hargaAwalTampil;
                var konfirmReset = form.querySelector('input[name="konfirmasi_ubah_harga"]');
                if (konfirmReset) {
                    konfirmReset.value = '0';
                }
            }
        });
    }

    function handleKasirFormSubmit(form, e) {
        var hargaInput = form.querySelector('input[name="harga_satuan"]');
        if (!hargaInput) {
            return;
        }

        var hargaAwal = parseFloat(hargaInput.getAttribute('data-harga-awal') || '0');
        var hargaBaru = parseHargaInputPenjualan(hargaInput.value);
        var hargaAwalTampil = hargaInput.getAttribute('data-harga-awal-tampil') || hargaInput.value;

        if (Math.abs(hargaBaru - hargaAwal) < 0.01) {
            return;
        }

        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi',
            html: 'Anda akan merubah harga satuan?<br/>Jika Ya, data persediaan akan ada perubahan daftar barang baru yang namanya sama tetapi dengan harga satuan yang berbeda.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed) {
                var konfirm = form.querySelector('input[name="konfirmasi_ubah_harga"]');
                if (!konfirm) {
                    konfirm = document.createElement('input');
                    konfirm.type = 'hidden';
                    konfirm.name = 'konfirmasi_ubah_harga';
                    form.appendChild(konfirm);
                }
                konfirm.value = '1';
                form.submit();
            } else {
                hargaInput.value = hargaAwalTampil;
            }
        });
    }

    function initFormUbahBarangPenjualan() {
        if (typeof Swal === 'undefined') {
            return;
        }

        document.querySelectorAll('form.penjualan-form-ubah-barang').forEach(function(form) {
            if (form.getAttribute('data-penjualan-ubah-init') === '1') {
                return;
            }
            form.setAttribute('data-penjualan-ubah-init', '1');

            form.addEventListener('submit', function(e) {
                if (isRekapUbahForm(form)) {
                    handleRekapFormSubmit(form, e);
                    return;
                }
                handleKasirFormSubmit(form, e);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFormUbahBarangPenjualan);
    } else {
        initFormUbahBarangPenjualan();
    }

    if (window.jQuery) {
        jQuery(document).on('shown.bs.modal', '.modal', function() {
            initFormUbahBarangPenjualan();
        });
    }
})();
</script>
