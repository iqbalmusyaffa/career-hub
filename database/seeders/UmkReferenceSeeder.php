<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UmkReference;

class UmkReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $umkData = [
            // DKI JAKARTA
            ['province' => 'DKI JAKARTA', 'city_district' => 'DKI Jakarta', 'umk_amount' => 5396761, 'legal_decree' => 'KEPUTUSAN GUBERNUR DKI JAKARTA NOMOR 1242 TAHUN 2025'],
            ['province' => 'DKI JAKARTA', 'city_district' => 'Jakarta Selatan', 'umk_amount' => 5396761, 'legal_decree' => 'KEPUTUSAN GUBERNUR DKI JAKARTA NOMOR 1242 TAHUN 2025'],
            ['province' => 'DKI JAKARTA', 'city_district' => 'Jakarta Pusat', 'umk_amount' => 5396761, 'legal_decree' => 'KEPUTUSAN GUBERNUR DKI JAKARTA NOMOR 1242 TAHUN 2025'],
            ['province' => 'DKI JAKARTA', 'city_district' => 'Jakarta Barat', 'umk_amount' => 5396761, 'legal_decree' => 'KEPUTUSAN GUBERNUR DKI JAKARTA NOMOR 1242 TAHUN 2025'],
            ['province' => 'DKI JAKARTA', 'city_district' => 'Jakarta Timur', 'umk_amount' => 5396761, 'legal_decree' => 'KEPUTUSAN GUBERNUR DKI JAKARTA NOMOR 1242 TAHUN 2025'],
            ['province' => 'DKI JAKARTA', 'city_district' => 'Jakarta Utara', 'umk_amount' => 5396761, 'legal_decree' => 'KEPUTUSAN GUBERNUR DKI JAKARTA NOMOR 1242 TAHUN 2025'],

            // JAWA BARAT
            ['province' => 'JAWA BARAT', 'city_district' => 'Kota Bekasi', 'umk_amount' => 5999443, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Karawang', 'umk_amount' => 5886853, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Bekasi', 'umk_amount' => 5938885, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Purwakarta', 'umk_amount' => 5052856, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Subang', 'umk_amount' => 3737482, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kota Depok', 'umk_amount' => 5522662, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kota Bogor', 'umk_amount' => 5437203, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Bogor', 'umk_amount' => 5161769, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Sukabumi', 'umk_amount' => 3831926, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Cianjur', 'umk_amount' => 3316191, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kota Sukabumi', 'umk_amount' => 3192807, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kota Bandung', 'umk_amount' => 4737678, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kota Cimahi', 'umk_amount' => 4090568, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Bandung Barat', 'umk_amount' => 3984711, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Sumedang', 'umk_amount' => 3949856, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Bandung', 'umk_amount' => 3972202, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Indramayu', 'umk_amount' => 2910254, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kota Cirebon', 'umk_amount' => 2878646, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Cirebon', 'umk_amount' => 2880798, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Majalengka', 'umk_amount' => 2595368, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Kuningan', 'umk_amount' => 2369380, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kota Tasikmalaya', 'umk_amount' => 2980336, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Tasikmalaya', 'umk_amount' => 2871874, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Garut', 'umk_amount' => 2472227, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Ciamis', 'umk_amount' => 2373644, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kabupaten Pangandaran', 'umk_amount' => 2351250, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],
            ['province' => 'JAWA BARAT', 'city_district' => 'Kota Banjar', 'umk_amount' => 2361241, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA BARAT NOMOR 561.7/Kep.862-Kesra/2025'],

            // BANTEN
            ['province' => 'BANTEN', 'city_district' => 'Kabupaten Pandeglang', 'umk_amount' => 3360078, 'legal_decree' => 'KEPUTUSAN GUBERNUR BANTEN NOMOR 703 TAHUN 2025'],
            ['province' => 'BANTEN', 'city_district' => 'Kabupaten Lebak', 'umk_amount' => 3330011, 'legal_decree' => 'KEPUTUSAN GUBERNUR BANTEN NOMOR 703 TAHUN 2025'],
            ['province' => 'BANTEN', 'city_district' => 'Kabupaten Tangerang', 'umk_amount' => 5210377, 'legal_decree' => 'KEPUTUSAN GUBERNUR BANTEN NOMOR 703 TAHUN 2025'],
            ['province' => 'BANTEN', 'city_district' => 'Kabupaten Serang', 'umk_amount' => 5178521, 'legal_decree' => 'KEPUTUSAN GUBERNUR BANTEN NOMOR 703 TAHUN 2025'],
            ['province' => 'BANTEN', 'city_district' => 'Kota Tangerang', 'umk_amount' => 5399406, 'legal_decree' => 'KEPUTUSAN GUBERNUR BANTEN NOMOR 703 TAHUN 2025'],
            ['province' => 'BANTEN', 'city_district' => 'Kota Cilegon', 'umk_amount' => 5469923, 'legal_decree' => 'KEPUTUSAN GUBERNUR BANTEN NOMOR 703 TAHUN 2025'],
            ['province' => 'BANTEN', 'city_district' => 'Kota Serang', 'umk_amount' => 4665928, 'legal_decree' => 'KEPUTUSAN GUBERNUR BANTEN NOMOR 703 TAHUN 2025'],
            ['province' => 'BANTEN', 'city_district' => 'Kota Tangerang Selatan', 'umk_amount' => 5247870, 'legal_decree' => 'KEPUTUSAN GUBERNUR BANTEN NOMOR 703 TAHUN 2025'],

            // JAWA TENGAH
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Cilacap', 'umk_amount' => 2773184, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Banyumas', 'umk_amount' => 2474599, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Purbalingga', 'umk_amount' => 2474722, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Banjarnegara', 'umk_amount' => 2327813, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Kebumen', 'umk_amount' => 2400000, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Purworejo', 'umk_amount' => 2401962, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Wonosobo', 'umk_amount' => 2455038, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Magelang', 'umk_amount' => 2607790, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Boyolali', 'umk_amount' => 2537949, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Klaten', 'umk_amount' => 2538691, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Sukoharjo', 'umk_amount' => 2500000, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Wonogiri', 'umk_amount' => 2335126, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Karanganyar', 'umk_amount' => 2592154, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Sragen', 'umk_amount' => 2337700, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Grobogan', 'umk_amount' => 2399186, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Blora', 'umk_amount' => 2345695, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Rembang', 'umk_amount' => 2386305, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Pati', 'umk_amount' => 2485000, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Kudus', 'umk_amount' => 2818585, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Jepara', 'umk_amount' => 2756501, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Demak', 'umk_amount' => 3122805, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Semarang', 'umk_amount' => 2940088, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Temanggung', 'umk_amount' => 2397000, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Kendal', 'umk_amount' => 2992994, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Batang', 'umk_amount' => 2708520, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Pekalongan', 'umk_amount' => 2633700, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Pemalang', 'umk_amount' => 2433254, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Tegal', 'umk_amount' => 2484162, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kabupaten Brebes', 'umk_amount' => 2400350, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kota Magelang', 'umk_amount' => 2429285, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kota Surakarta', 'umk_amount' => 2570000, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kota Salatiga', 'umk_amount' => 2698273, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kota Semarang', 'umk_amount' => 3701709, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kota Pekalongan', 'umk_amount' => 2700926, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],
            ['province' => 'JAWA TENGAH', 'city_district' => 'Kota Tegal', 'umk_amount' => 2526510, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TENGAH NOMOR 100.3.3.1/505 TAHUN 2025'],

            // DIY YOGYAKARTA
            ['province' => 'DAERAH ISTIMEWA YOGYAKARTA', 'city_district' => 'Kota Yogyakarta', 'umk_amount' => 2827593, 'legal_decree' => 'KEPUTUSAN GUBERNUR DIY NOMOR 443 TAHUN 2025'],
            ['province' => 'DAERAH ISTIMEWA YOGYAKARTA', 'city_district' => 'Kabupaten Sleman', 'umk_amount' => 2624387, 'legal_decree' => 'KEPUTUSAN GUBERNUR DIY NOMOR 443 TAHUN 2025'],
            ['province' => 'DAERAH ISTIMEWA YOGYAKARTA', 'city_district' => 'Kabupaten Bantul', 'umk_amount' => 2509001, 'legal_decree' => 'KEPUTUSAN GUBERNUR DIY NOMOR 443 TAHUN 2025'],
            ['province' => 'DAERAH ISTIMEWA YOGYAKARTA', 'city_district' => 'Kabupaten Kulon Progo', 'umk_amount' => 2504520, 'legal_decree' => 'KEPUTUSAN GUBERNUR DIY NOMOR 443 TAHUN 2025'],
            ['province' => 'DAERAH ISTIMEWA YOGYAKARTA', 'city_district' => 'Kabupaten Gunungkidul', 'umk_amount' => 2468378, 'legal_decree' => 'KEPUTUSAN GUBERNUR DIY NOMOR 443 TAHUN 2025'],

            // JAWA TIMUR
            ['province' => 'JAWA TIMUR', 'city_district' => 'Kota Surabaya', 'umk_amount' => 5288796, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TIMUR NOMOR 100.3.3.1/937/013/2025'],
            ['province' => 'JAWA TIMUR', 'city_district' => 'Kabupaten Gresik', 'umk_amount' => 5195401, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TIMUR NOMOR 100.3.3.1/937/013/2025'],
            ['province' => 'JAWA TIMUR', 'city_district' => 'Kabupaten Sidoarjo', 'umk_amount' => 5191541, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TIMUR NOMOR 100.3.3.1/937/013/2025'],
            ['province' => 'JAWA TIMUR', 'city_district' => 'Kabupaten Pasuruan', 'umk_amount' => 5187681, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TIMUR NOMOR 100.3.3.1/937/013/2025'],
            ['province' => 'JAWA TIMUR', 'city_district' => 'Kabupaten Mojokerto', 'umk_amount' => 5176101, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TIMUR NOMOR 100.3.3.1/937/013/2025'],
            ['province' => 'JAWA TIMUR', 'city_district' => 'Kabupaten Malang', 'umk_amount' => 3802862, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TIMUR NOMOR 100.3.3.1/937/013/2025'],
            ['province' => 'JAWA TIMUR', 'city_district' => 'Kota Malang', 'umk_amount' => 3736101, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TIMUR NOMOR 100.3.3.1/937/013/2025'],
            ['province' => 'JAWA TIMUR', 'city_district' => 'Kota Batu', 'umk_amount' => 3562484, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TIMUR NOMOR 100.3.3.1/937/013/2025'],
            ['province' => 'JAWA TIMUR', 'city_district' => 'Kota Pasuruan', 'umk_amount' => 3555301, 'legal_decree' => 'KEPUTUSAN GUBERNUR JAWA TIMUR NOMOR 100.3.3.1/937/013/2025'],

            // BALI
            ['province' => 'BALI', 'city_district' => 'Kabupaten Badung', 'umk_amount' => 3791002, 'legal_decree' => 'KEPUTUSAN GUBERNUR BALI NOMOR 1021/03-M/HK/2025'],
            ['province' => 'BALI', 'city_district' => 'Kota Denpasar', 'umk_amount' => 3499878, 'legal_decree' => 'KEPUTUSAN GUBERNUR BALI NOMOR 1021/03-M/HK/2025'],
            ['province' => 'BALI', 'city_district' => 'Kabupaten Gianyar', 'umk_amount' => 3316798, 'legal_decree' => 'KEPUTUSAN GUBERNUR BALI NOMOR 1021/03-M/HK/2025'],
            ['province' => 'BALI', 'city_district' => 'Kabupaten Tabanan', 'umk_amount' => 3287678, 'legal_decree' => 'KEPUTUSAN GUBERNUR BALI NOMOR 1021/03-M/HK/2025'],

            // KEPULAUAN RIAU & RIAU
            ['province' => 'KEPULAUAN RIAU', 'city_district' => 'Kota Batam', 'umk_amount' => 5357982, 'legal_decree' => 'SURAT WALIKOTA BATAM NOMOR 1134/500.15.14.1/XII/2025'],
            ['province' => 'RIAU', 'city_district' => 'Kota Pekanbaru', 'umk_amount' => 3998179, 'legal_decree' => 'KEPUTUSAN GUBERNUR RIAU NOMOR Kpts.1164/XII/2025'],
            ['province' => 'RIAU', 'city_district' => 'Kota Dumai', 'umk_amount' => 4431174, 'legal_decree' => 'KEPUTUSAN GUBERNUR RIAU NOMOR Kpts.1164/XII/2025'],
            ['province' => 'KEPULAUAN RIAU', 'city_district' => 'Kabupaten Bintan', 'umk_amount' => 4583221, 'legal_decree' => 'KEPUTUSAN GUBERNUR KEPULAUAN RIAU NOMOR 1333 TAHUN 2025'],
            ['province' => 'KEPULAUAN RIAU', 'city_district' => 'Kota Tanjungpinang', 'umk_amount' => 3879520, 'legal_decree' => 'KEPUTUSAN GUBERNUR KEPULAUAN RIAU NOMOR 1332 TAHUN 2025'],

            // SULAWESI & KALIMANTAN
            ['province' => 'KALIMANTAN SELATAN', 'city_district' => 'Kota Banjarmasin', 'umk_amount' => 3855894, 'legal_decree' => 'KEPUTUSAN GUBERNUR KALIMANTAN SELATAN NOMOR 100.3.3.1/01107/KUM/2025'],
            ['province' => 'KALIMANTAN SELATAN', 'city_district' => 'Kota Banjarbaru', 'umk_amount' => 3843037, 'legal_decree' => 'KEPUTUSAN GUBERNUR KALIMANTAN SELATAN NOMOR 100.3.3.1/01107/KUM/2025'],
            ['province' => 'SULAWESI SELATAN', 'city_district' => 'Kota Makassar', 'umk_amount' => 4148179, 'legal_decree' => 'KEPUTUSAN GUBERNUR SULAWESI SELATAN NOMOR 2142/XII/TAHUN 2025'],
            ['province' => 'SULAWESI TENGAH', 'city_district' => 'Kota Palu', 'umk_amount' => 3619466, 'legal_decree' => 'KEPUTUSAN GUBERNUR SULAWESI TENGAH NOMOR 500.15.14.1/486/DIS.NAKERTRANS G.A/2025'],
        ];

        foreach ($umkData as $data) {
            UmkReference::updateOrCreate(
                [
                    'province' => $data['province'],
                    'city_district' => $data['city_district'],
                ],
                [
                    'umk_amount' => $data['umk_amount'],
                    'year' => 2026,
                    'legal_decree' => $data['legal_decree'],
                ]
            );
        }
    }
}
