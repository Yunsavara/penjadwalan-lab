@php
    use Carbon\Carbon;
@endphp

<div>
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Pengajuan Booking</h5>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Kode Booking:</strong><br>{{ $pengajuan->kode_booking ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Pengajuan Oleh:</strong><br>{{ $pengajuan->user->nama_pengguna ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Prioritas:</strong><br>{{ $pengajuan->user->role->prioritas_peran ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Lokasi:</strong><br>{{ $pengajuan->lokasi->nama_lokasi ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Keperluan:</strong><br>{{ $pengajuan->keperluan_pengajuan_booking ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Balasan:</strong><br>{{ $pengajuan->balasan_pengajuan_booking ?? 'Tidak Diketahui' }}</p>
                        <p><strong>Detail Jadwal Booking:</strong></p>
                        <ul>
                            @forelse($pengajuan->jadwalBookings as $jadwal)
                                <li>
                                    {{ Carbon::parse($jadwal->tanggal_jadwal)->locale('id')->translatedFormat('d F Y') }},
                                    {{ Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ Carbon::parse($jadwal->jam_selesai)->format('H:i') }},
                                    {{ ucfirst($jadwal->status) }},
                                    {{ $jadwal->laboratoriumUnpam->nama_laboratorium ?? '-' }}
                                </li>
                            @empty
                                <li>Tidak ada jadwal booking.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-backdrop fade show"></div>
    @endif
</div>
