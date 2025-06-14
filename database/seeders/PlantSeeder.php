<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;

class PlantSeeder extends Seeder
{
    public function run()
    {
        $plants = [
            [
                'name' => 'Kangkung',
                'suhu' => 28,
                'description' => 'Kangkung adalah tanaman air yang cepat tumbuh dan cocok untuk sistem hidroponik karena tidak memerlukan media tanah.',
                'image' => 'kangkung.png',
                'cara_menanam' => "1. Siapkan benih kangkung unggul\n2. Rendam benih 8-12 jam\n3. Semai di rockwool basah\n4. Pindahkan ke sistem NFT atau DFT saat berdaun 2-3\n5. Beri nutrisi AB Mix\n6. Jaga pH 5.5-6.5 dan EC 1.5-2.0",
                'kebutuhan_lingkungan' => "<strong>Suhu:</strong> 26-30°C\n<strong>Kelembaban:</strong> 60-80%\n<strong>Cahaya:</strong> 10-12 jam/hari\n<strong>pH:</strong> 5.5-6.5",
                'waktu_panen' => '21-30 hari setelah tanam.',
                'tips_perawatan' => "Pastikan air tidak menggenang\nJaga sirkulasi nutrisi\nHindari serangan hama ulat dan kutu\nPangkas secara berkala untuk merangsang tunas baru",
            ],
            [
                'name' => 'Bayam',
                'suhu' => 26,
                'description' => 'Bayam merupakan sayuran daun hijau yang mudah tumbuh dan memiliki kandungan zat besi tinggi.',
                'image' => 'bayam.png',
                'cara_menanam' => "1. Gunakan benih bayam segar\n2. Rendam 6-8 jam\n3. Semai di rockwool\n4. Setelah tumbuh 2 daun, pindahkan ke sistem hidroponik\n5. Gunakan nutrisi AB Mix sayur\n6. Jaga pH 5.5-6.5 dan EC 1.0-1.8",
                'kebutuhan_lingkungan' => "<strong>Suhu:</strong> 24-28°C\n<strong>Kelembaban:</strong> 60-70%\n<strong>Cahaya:</strong> 8-10 jam/hari\n<strong>pH:</strong> 5.5-6.5",
                'waktu_panen' => '25-35 hari setelah tanam.',
                'tips_perawatan' => "Ganti nutrisi tiap minggu\nHindari genangan air\nJaga kelembaban tetap stabil\nLakukan pemangkasan bila terlalu rimbun",
            ],
            [
                'name' => 'Pakcoy',
                'suhu' => 25,
                'description' => 'Pakcoy atau bok choy adalah jenis sawi yang cocok untuk sistem hidroponik dan memiliki tekstur renyah.',
                'image' => 'pakcoy.png',
                'cara_menanam' => "1. Siapkan benih pakcoy berkualitas\n2. Rendam benih selama 6-12 jam\n3. Semai di rockwool\n4. Setelah 7-10 hari, pindahkan ke sistem NFT\n5. Beri nutrisi sayuran daun\n6. Jaga pH 6.0-6.5 dan EC 1.5-2.0",
                'kebutuhan_lingkungan' => "<strong>Suhu:</strong> 20-28°C\n<strong>Kelembaban:</strong> 60-70%\n<strong>Cahaya:</strong> 10-12 jam/hari\n<strong>pH:</strong> 6.0-6.5",
                'waktu_panen' => '30-40 hari setelah tanam.',
                'tips_perawatan' => "Pantau daun untuk mencegah bercak\nBersihkan akar dari lendir\nGanti air jika keruh\nPanen saat daun masih muda untuk rasa terbaik",
            ],
            [
                'name' => 'Daun Bawang',
                'suhu' => 24,
                'description' => 'Daun bawang adalah tanaman aromatik yang bisa tumbuh optimal di hidroponik dengan pencahayaan cukup.',
                'image' => 'daun bawang.png',
                'cara_menanam' => "1. Gunakan umbi atau benih daun bawang\n2. Semai pada rockwool lembab\n3. Pindahkan setelah tumbuh 2-3 daun\n4. Tempatkan di sistem DFT\n5. Beri nutrisi AB Mix sayuran\n6. pH optimal 6.0-6.5 dan EC 1.6-2.4",
                'kebutuhan_lingkungan' => "<strong>Suhu:</strong> 18-25°C\n<strong>Kelembaban:</strong> 60-70%\n<strong>Cahaya:</strong> 10-12 jam/hari\n<strong>pH:</strong> 6.0-6.5",
                'waktu_panen' => '50-60 hari setelah tanam.',
                'tips_perawatan' => "Pastikan akar tidak terendam total\nHindari genangan\nBersihkan sistem secara rutin\nTambahkan batang tanaman secara berkala untuk anakan baru",
            ],
            [
                'name' => 'Selada',
                'suhu' => 23,
                'description' => 'Selada merupakan tanaman daun yang populer dalam hidroponik karena cepat panen dan mudah dirawat.',
                'image' => 'selada.png',
                'cara_menanam' => "1. Gunakan benih selada segar\n2. Semai di rockwool selama 7-10 hari\n3. Pindahkan ke sistem hidroponik (NFT/DFT)\n4. Gunakan nutrisi AB Mix khusus daun\n5. Jaga pH 5.5-6.2 dan EC 0.8-1.2\n6. Hindari overwatering",
                'kebutuhan_lingkungan' => "<strong>Suhu:</strong> 18-24°C\n<strong>Kelembaban:</strong> 50-70%\n<strong>Cahaya:</strong> 12-14 jam/hari\n<strong>pH:</strong> 5.5-6.2",
                'waktu_panen' => '30-45 hari setelah tanam.',
                'tips_perawatan' => "Lindungi dari sinar matahari langsung\nHindari suhu tinggi\nGunakan kipas jika terlalu lembab\nPanen saat daun padat namun masih renyah",
            ],
        ];

        foreach ($plants as $plant) {
            Plant::create($plant);
        }
    }
}
