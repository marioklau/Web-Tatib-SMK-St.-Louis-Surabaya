@extends('layouts.main')

@section('title', 'Form Input Pelanggaran')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Form Input Pelanggaran</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('input_pelanggaran.store') }}" class="bg-white rounded shadow p-6">
        @csrf

        {{-- Kelas --}}
        <div class="mb-4">
            <label for="kelas">Kelas</label>
            <select name="kelas_id" id="kelas-select" class="w-full p-2 border rounded">
                <option value="">-- Pilih Kelas --</option>
                <option value="all">-- Semua Kelas --</option> {{-- TAMBAHKAN OPSI INI --}}
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>

        {{-- Nama siswa + counter --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 items-end">
            <div>
                <label class="block mb-1">Nama Siswa</label>
                <select name="siswa_id" id="siswa-select" class="w-full p-2 border rounded" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($siswa as $student)
                        <option
                            value="{{ $student->id }}"
                            data-kelas="{{ $student->kelas_id }}"
                            data-r="{{ $student->ringan_count ?? 0 }}"
                            data-b="{{ $student->berat_count ?? 0 }}"
                            data-sb="{{ $student->sangat_berat_count ?? 0 }}"
                            data-total-bobot="{{ $student->pelanggaran_sum_total_bobot ?? 0 }}"
                            {{ old('siswa_id') == $student->id ? 'selected' : '' }}
                        >
                            {{ $student->nama_siswa }} - ({{ $student->kelas->kode_kelas ?? 'Tanpa Kelas' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2 col-span-3">
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">R</label>
                    <input type="text" id="count-r" class="w-14 p-1 text-xs border-2 rounded text-center bg-green-100 text-green-800" readonly>
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">B</label>
                    <input type="text" id="count-b" class="w-14 p-1 text-xs border-2 rounded text-center bg-yellow-100 text-yellow-800" readonly>
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">SB</label>
                    <input type="text" id="count-sb" class="w-14 p-1 text-xs border-2 rounded text-center bg-red-100 text-red-800" readonly>
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">Total Bobot</label>
                    <input type="text" id="total-bobot-display" class="w-24 p-1 text-xs border-2 rounded text-center bg-blue-100 text-blue-800" readonly>
                </div>
            </div>
        </div>

        {{-- Jenis pelanggaran & kategori --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block mb-1">Jenis Pelanggaran</label>
                <select name="jenis_id" id="jenis-select" class="w-full border" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach ($jenis as $j)
                        @if($j->bobot_poin >= 1)
                            <option
                                value="{{ $j->id }}"
                                data-kategori-id="{{ $j->kategori->id }}"
                                data-kategori-nama="{{ $j->kategori->nama_kategori }}"
                                data-bobot="{{ $j->bobot_poin }}"
                                {{ old('jenis_id') == $j->id ? 'selected' : '' }}
                            >
                                {{ $j->bentuk_pelanggaran }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Kategori</label>
                <input type="text" id="kategori-display" class="border-2 text-gray-600 text-center" readonly>
                <input type="hidden" name="kategori_id" id="kategori-id">
            </div>
        </div>

        <input type="hidden" name="bobot" id="bobot-input" value="{{ old('bobot') }}">

        {{-- Alur pembinaan --}}
        <div class="mt-4 bg-gray-50 p-4 rounded">
            <label class="block mb-2 font-semibold">Alur Pembinaan:</label>
            <div id="alur-pembinaan" class="text-sm text-gray-700">
                <p class="text-gray-500">Pilih jenis pelanggaran untuk melihat alur pembinaan</p>
            </div>
        </div>

        {{-- Keputusan tindakan --}}
        <div class="mt-4">
            <label class="block mb-1">Pilih Keputusan Tindakan (Minimal 2)</label>
            <div id="keputusan-container" class="border p-3 rounded bg-gray-50 max-h-60 overflow-y-auto hidden">
                <div class="space-y-2">
                    <!-- Checklist options akan diisi oleh JavaScript -->
                </div>
                <div id="keputusan-error" class="text-red-500 text-sm mt-2 hidden">Pilih minimal 2 keputusan tindakan</div>
            </div>
            <input type="hidden" name="keputusan_tindakan_terpilih" id="keputusan-input" required>
        </div>  

        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <button type="button" onclick="history.back()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Kembali</button>
        </div>        
    </form>
</div>

<script>
$(function () {
  // Init Select2
  $('#kelas-select, #jenis-select').select2({ placeholder: "-- Pilih --", allowClear: true });
  $('#siswa-select').select2({ placeholder: "-- Pilih --", allowClear: true });

  const $kelas  = $('#kelas-select');
  const $siswa  = $('#siswa-select');
  const $keputusanContainer = $('#keputusan-container');
  const $keputusanInput = $('#keputusan-input');
  const $keputusanError = $('#keputusan-error');

  // Simpan siswa yang dipilih sebelum refresh
  let selectedSiswaId = null;

  // Helper: rebuild opsi siswa dari data AJAX
  function buildSiswaOptions(items, preserveSelection = false) {
    // Simpan siswa yang sedang dipilih sebelum refresh
    if (preserveSelection) {
      selectedSiswaId = $siswa.val();
    }

    // Hancurkan dulu select2 supaya aman manipulasi option
    $siswa.select2('destroy');
    $siswa.empty().append('<option value=""></option>'); // placeholder

    items.forEach(function (s) {
      const opt = $('<option>', {
        value: s.id,
        text: `${s.nama_siswa} - (${s.kelas_kode ?? 'Tanpa Kelas'})`
      })
      .attr('data-kelas', s.kelas_id)
      .attr('data-r', s.ringan_count ?? 0)
      .attr('data-b', s.berat_count ?? 0)
      .attr('data-sb', s.sangat_berat_count ?? 0)
      .attr('data-total-bobot', s.total_bobot ?? 0);

      $siswa.append(opt);
    });

    // Kembalikan pilihan siswa sebelumnya jika ada
    if (preserveSelection && selectedSiswaId) {
      $siswa.val(selectedSiswaId);
    }

    $siswa.prop('disabled', items.length === 0);
    $siswa.select2({ placeholder: "-- Pilih --", allowClear: true });
    
    // Trigger change untuk update counter
    $siswa.trigger('change');
  }

  // Saat kelas berubah: ambil siswa via AJAX, rebuild opsi
  $kelas.on('change', function () {
    const kelasId = $(this).val();

    // Reset counter setiap ganti kelas
    $('#count-r, #count-b, #count-sb, #total-bobot-display').val('');

    if (!kelasId) {
      buildSiswaOptions([], true); // kosongkan, tapi preserve selection
      return;
    }

    const url = "{{ route('ajax.siswa.byKelas', ':id') }}".replace(':id', kelasId);
    $.getJSON(url)
      .done(function (data) {
        buildSiswaOptions(data || [], true); // preserve selection
      })
      .fail(function () {
        buildSiswaOptions([], true); // preserve selection
      });
  });

  // Saat siswa dipilih: otomatis set kelas sesuai siswa yang dipilih
  $siswa.on('change', function() {
    const selectedOption = $(this).find('option:selected');
    const kelasId = selectedOption.data('kelas');
    
    // Jika siswa dipilih dan memiliki kelas, set otomatis field kelas
    // TAPI hanya jika kelas belum dipilih atau berbeda dengan kelas siswa
    if (selectedOption.val() && kelasId && (!$kelas.val() || $kelas.val() !== kelasId.toString())) {
      $kelas.val(kelasId).trigger('change');
    }
    
    updateCounts(); // Update counter
  });

  // Update counter ketika siswa dipilih
  function updateCounts() {
    const selected = $siswa.find('option:selected');
    $('#count-r').val(selected.data('r') ?? 0);
    $('#count-b').val(selected.data('b') ?? 0);
    $('#count-sb').val(selected.data('sb') ?? 0);
    $('#total-bobot-display').val(selected.data('total-bobot') ?? 0);
  }

  // Kategori, alur pembinaan, keputusan
  const allSanksiData = @json($sanksi);

  $('#jenis-select').on('change', function () {
    const selectedJenis = $(this).find(':selected');
    const kategoriId = selectedJenis.data('kategori-id');
    const kategoriNama = selectedJenis.data('kategori-nama');
    const bobotPelanggaran = selectedJenis.data('bobot');

    $('#kategori-id').val(kategoriId);
    $('#kategori-display').val(kategoriNama);
    $('#bobot-input').val(bobotPelanggaran);

    showAlurPembinaan(kategoriId, bobotPelanggaran);
    updateKeputusanOptions(kategoriId, bobotPelanggaran);
  });

  function showAlurPembinaan(kategoriId, bobotBaru) {
    const alurContainer = $('#alur-pembinaan');
    const selected = $siswa.find('option:selected');
    const totalSebelumnya = parseInt(selected.data('total-bobot')) || 0;
    const totalBobot = totalSebelumnya + parseInt(bobotBaru || 0);

    const sanksiKategori = allSanksiData
      .filter(s => s.kategori_id == kategoriId)
      .sort((a, b) => (a.bobot_min || 0) - (b.bobot_min || 0));

    let sanksi = sanksiKategori.find(s => totalBobot >= s.bobot_min && totalBobot <= s.bobot_max) || sanksiKategori[0];

    let html = '<div class="space-y-2">';
    if (sanksi && Array.isArray(sanksi.nama_sanksi)) {
      html += `<h4 class="font-semibold">Tingkat (Total Bobot ${totalBobot} dalam range ${sanksi.bobot_min}-${sanksi.bobot_max}):</h4>`;
      html += '<ol class="list-decimal pl-5">';
      sanksi.nama_sanksi.forEach(item => html += `<li class="mb-1">${item}</li>`);
      html += '</ol>';
    } else {
      html += '<p class="text-gray-500">Tidak ada alur pembinaan tersedia.</p>';
    }
    html += '</div>';
    alurContainer.html(html);
  }

  function updateKeputusanOptions(kategoriId, bobotBaru) {
    const selected = $siswa.find('option:selected');
    const totalSebelumnya = parseInt(selected.data('total-bobot')) || 0;
    const totalBobot = totalSebelumnya + parseInt(bobotBaru || 0);

    const sanksiKategori = allSanksiData
      .filter(s => s.kategori_id == kategoriId)
      .sort((a, b) => (a.bobot_min || 0) - (b.bobot_min || 0));

    let sanksi = sanksiKategori.find(s => totalBobot >= s.bobot_min && totalBobot <= s.bobot_max) || sanksiKategori[0];

    // Kosongkan container keputusan
    $keputusanContainer.find('.space-y-2').empty();
    $keputusanInput.val('');
    $keputusanError.addClass('hidden');

    if (sanksi && Array.isArray(sanksi.keputusan_tindakan)) {
      // Tampilkan container
      $keputusanContainer.removeClass('hidden');
      
      // Buat checklist options
      sanksi.keputusan_tindakan.forEach((k, index) => {
        if (k) {
          const checkboxId = `keputusan-${index}`;
          const checkbox = `
            <div class="flex items-center">
              <input type="checkbox" id="${checkboxId}" name="keputusan_checkbox" value="${k}" class="keputusan-checkbox mr-2">
              <label for="${checkboxId}" class="text-sm">${k}</label>
            </div>
          `;
          $keputusanContainer.find('.space-y-2').append(checkbox);
        }
      });

      // Event handler untuk checkbox
      $('.keputusan-checkbox').off('change').on('change', function() {
        updateKeputusanInput();
      });
    } else {
      $keputusanContainer.addClass('hidden');
    }
  }

  function updateKeputusanInput() {
    const selectedCheckboxes = $('.keputusan-checkbox:checked');
    const selectedValues = selectedCheckboxes.map(function() {
      return this.value;
    }).get();

    // Validasi: minimal 2 opsi dipilih
    if (selectedValues.length < 2) {
      $keputusanError.removeClass('hidden');
      $keputusanInput.val('');
    } else {
      $keputusanError.addClass('hidden');
      $keputusanInput.val(JSON.stringify(selectedValues));
    }
  }

  // Validasi form sebelum submit
  $('form').on('submit', function(e) {
    const selectedCheckboxes = $('.keputusan-checkbox:checked');
    if (selectedCheckboxes.length < 2) {
      e.preventDefault();
      $keputusanError.removeClass('hidden');
      $keputusanContainer.addClass('border-red-500');
      // Scroll ke error
      $('html, body').animate({
        scrollTop: $keputusanContainer.offset().top - 100
      }, 500);
    }
  });

  // Restore old input (jika validasi gagal)
  @if(old('siswa_id'))
    // Cari kelas untuk siswa lama lewat AJAX terlebih dulu
    const oldSiswaId = '{{ old('siswa_id') }}';
    const oldKelasId = '{{ old('kelas_id') }}';
    
    // Restore keputusan tindakan jika ada
    @if(old('keputusan_tindakan_terpilih'))
    const oldKeputusan = {!! json_encode(old('keputusan_tindakan_terpilih')) !!};
    @endif

    if (oldKelasId) {
      $kelas.val(String(oldKelasId)).trigger('change');
      setTimeout(() => {
        const urlOld = "{{ route('ajax.siswa.byKelas', ':id') }}".replace(':id', oldKelasId);
        $.getJSON(urlOld).done(function (data) {
          buildSiswaOptions(data || [], false);
          $siswa.val(String(oldSiswaId)).trigger('change');
          @if(old('jenis_id'))
            $('#jenis-select').val('{{ old('jenis_id') }}').trigger('change');
            setTimeout(() => {
              // Restore checklist keputusan
              @if(old('keputusan_tindakan_terpilih'))
                try {
                  const keputusanArray = Array.isArray(oldKeputusan) ? oldKeputusan : JSON.parse(oldKeputusan);
                  if (Array.isArray(keputusanArray)) {
                    keputusanArray.forEach(value => {
                      $(`.keputusan-checkbox[value="${value}"]`).prop('checked', true);
                    });
                    updateKeputusanInput();
                  }
                } catch (e) {
                  console.error('Error parsing keputusan:', e);
                }
              @endif
            }, 100);
          @endif
        });
      }, 50);
    } else {
      // Jika tidak ada old kelas_id, cari dari siswa yang dipilih
      const oldSiswaOption = $siswa.find(`option[value="${oldSiswaId}"]`);
      if (oldSiswaOption.length) {
        const kelasIdFromSiswa = oldSiswaOption.data('kelas');
        if (kelasIdFromSiswa) {
          $kelas.val(kelasIdFromSiswa).trigger('change');
          setTimeout(() => {
            $siswa.val(String(oldSiswaId)).trigger('change');
            // Restore checklist keputusan
            @if(old('keputusan_tindakan_terpilih'))
              setTimeout(() => {
                try {
                  const keputusanArray = Array.isArray(oldKeputusan) ? oldKeputusan : JSON.parse(oldKeputusan);
                  if (Array.isArray(keputusanArray)) {
                    keputusanArray.forEach(value => {
                      $(`.keputusan-checkbox[value="${value}"]`).prop('checked', true);
                    });
                    updateKeputusanInput();
                  }
                } catch (e) {
                  console.error('Error parsing keputusan:', e);
                }
              }, 100);
            @endif
          }, 50);
        }
      }
    }
  @endif
});
</script>


@endsection
