<div>
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Batalkan Pengajuan Booking</h5>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin <strong>membatalkan</strong> pengajuan booking ini?</p>
                        <p><strong>Kode Booking:</strong><br>{{ $pengajuan->kode_booking ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Keperluan:</strong><br>{{ $pengajuan->keperluan_pengajuan_booking ?? 'Tidak Diketahui' }}</p>
                        <hr>
                        <div class="mb-3">
                            <label for="alasanBatal"><strong>Alasan Pembatalan</strong></label>
                            <textarea wire:model.defer="alasanBatal" id="alasanBatal" class="form-control" style="resize:none; max-height:100px; min-height:100px" placeholder="Tuliskan alasan pembatalan..."></textarea>
                            @error('alasanBatal')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                        <button type="button" class="btn btn-danger" wire:click="prosesBatalkan">Batalkan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>