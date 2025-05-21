<div>
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Terima Pengajuan Booking</h5>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ Auth()->user()->lokasi_id }}</p>
                        <p>Apakah Anda yakin ingin <strong>menerima</strong> pengajuan booking ini?</p>
                        <p><strong>Kode Booking:</strong><br>{{ $pengajuan->kode_booking ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Pengajuan Oleh:</strong><br>{{ $pengajuan->user->nama_pengguna ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Keperluan:</strong><br>{{ $pengajuan->keperluan_pengajuan_booking ?? 'Tidak Diketahui' }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                        <button type="button" class="btn btn-success" wire:click="terimaPengajuan">Terima</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
