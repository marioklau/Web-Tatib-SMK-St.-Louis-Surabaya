<?php

// database/migrations/YYYY_MM_DD_HHMMSS_add_keputusan_tindakan_terpilih_to_pelanggaran_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            // Hapus foreign key jika sebelumnya ada `keputusan_tindakan_id`
            // $table->dropForeign(['keputusan_tindakan_id']);
            // $table->dropColumn('keputusan_tindakan_id');

            // Tambahkan kolom baru untuk menyimpan string keputusan tindakan yang dipilih
            $table->string('keputusan_tindakan_terpilih')->nullable()->after('sanksi_id'); // Atau setelah kolom lain yang relevan
        });
    }

    public function down(): void
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            $table->dropColumn('keputusan_tindakan_terpilih');
            // Jika Anda menghapus kolom keputusan_tindakan_id, Anda mungkin perlu menambahkannya kembali di sini jika ingin rollback
            // $table->foreignId('keputusan_tindakan_id')->nullable()->constrained('keputusan_tindakan')->onDelete('set null');
        });
    }
};
