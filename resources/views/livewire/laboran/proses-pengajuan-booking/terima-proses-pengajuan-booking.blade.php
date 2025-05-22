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
                        <p>Apakah Anda yakin ingin <strong>menerima</strong> pengajuan booking ini?</p>
                        <p><strong>Kode Booking:</strong><br>{{ $pengajuan->kode_booking ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Pengajuan Oleh:</strong><br>{{ $pengajuan->user->nama_pengguna ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Keperluan:</strong><br>{{ $pengajuan->keperluan_pengajuan_booking ?? 'Tidak Diketahui' }}</p>
                        <hr>
                        <div class="mb-3">
                            <label><strong>Mode Tolak Otomatis?</strong></label><br>
                            <input type="radio" wire:model.live="modeTolakOtomatis" value="otomatis" id="otomatis">
                            <label for="otomatis">Otomatis (tolak prioritas bawah)</label><br>
                            <input type="radio" wire:model.live="modeTolakOtomatis" value="manual" id="manual">
                            <label for="manual">Manual (tidak tolak prioritas bawah)</label>
                        </div>
                        @if($modeTolakOtomatis === 'otomatis')
                            <div class="mb-3">
                                <label for="balasanPrioritasBawah"><strong>Balasan Pengajuan Yang Ditolak</strong></label>
                                <textarea wire:model.defer="balasanPrioritasBawah" id="balasanPrioritasBawah" class="form-control" style="resize:none; max-height:100px; min-height:100px"></textarea>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                        <button type="button" class="btn btn-success" wire:click="prosesTerimaPengajuan">Terima</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
