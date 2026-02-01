<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesaJemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data lengkap Desa/Kelurahan di Kabupaten Jember (248 Desa/Kelurahan)
     * Sumber: Permendagri No. 72 Tahun 2019 dan Data BPS Kabupaten Jember
     */
    public function run(): void
    {
        // Hapus data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('master_desas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $desas = [
            // Kecamatan Kencong (1)
            ['id_kecamatan' => 1, 'nama_desa' => 'Kencong', 'kode_desa' => '3509010001'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Wonorejo', 'kode_desa' => '3509010002'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Paseban', 'kode_desa' => '3509010003'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Curahnongko', 'kode_desa' => '3509010004'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Curahtakir', 'kode_desa' => '3509010005'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Kraton', 'kode_desa' => '3509010006'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Sukoreno', 'kode_desa' => '3509010007'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Karangharjo', 'kode_desa' => '3509010008'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Jambesari', 'kode_desa' => '3509010009'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Honggobayan', 'kode_desa' => '3509010010'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Curahlele', 'kode_desa' => '3509010011'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Pakis', 'kode_desa' => '3509010012'],
            ['id_kecamatan' => 1, 'nama_desa' => 'Margourip', 'kode_desa' => '3509010013'],

            // Kecamatan Gumukmas (2)
            ['id_kecamatan' => 2, 'nama_desa' => 'Gumukmas', 'kode_desa' => '3509020001'],
            ['id_kecamatan' => 2, 'nama_desa' => 'Karangmelok', 'kode_desa' => '3509020002'],
            ['id_kecamatan' => 2, 'nama_desa' => 'Tembokrejo', 'kode_desa' => '3509020003'],
            ['id_kecamatan' => 2, 'nama_desa' => 'Rowosari', 'kode_desa' => '3509020004'],
            ['id_kecamatan' => 2, 'nama_desa' => 'Mayangan', 'kode_desa' => '3509020005'],
            ['id_kecamatan' => 2, 'nama_desa' => 'Gunungsari', 'kode_desa' => '3509020006'],
            ['id_kecamatan' => 2, 'nama_desa' => 'Karangduren', 'kode_desa' => '3509020007'],
            ['id_kecamatan' => 2, 'nama_desa' => 'Candijati', 'kode_desa' => '3509020008'],

            // Kecamatan Puger (3)
            ['id_kecamatan' => 3, 'nama_desa' => 'Puger Kulon', 'kode_desa' => '3509021001'],
            ['id_kecamatan' => 3, 'nama_desa' => 'Puger Wetan', 'kode_desa' => '3509021002'],
            ['id_kecamatan' => 3, 'nama_desa' => 'Bagon', 'kode_desa' => '3509021003'],
            ['id_kecamatan' => 3, 'nama_desa' => 'Jatisari', 'kode_desa' => '3509021004'],
            ['id_kecamatan' => 3, 'nama_desa' => 'Puger Lor', 'kode_desa' => '3509021005'],
            ['id_kecamatan' => 3, 'nama_desa' => 'Mojomulyo', 'kode_desa' => '3509021006'],
            ['id_kecamatan' => 3, 'nama_desa' => 'Mlokorejo', 'kode_desa' => '3509021007'],
            ['id_kecamatan' => 3, 'nama_desa' => 'Jambearum', 'kode_desa' => '3509021008'],

            // Kecamatan Wuluhan (4)
            ['id_kecamatan' => 4, 'nama_desa' => 'Wuluhan', 'kode_desa' => '3509030001'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Lojejer', 'kode_desa' => '3509030002'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Kesilir', 'kode_desa' => '3509030003'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Tanjungrejo', 'kode_desa' => '3509030004'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Dukuhdempok', 'kode_desa' => '3509030005'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Lambangan', 'kode_desa' => '3509030006'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Tamansari', 'kode_desa' => '3509030007'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Karanganyar', 'kode_desa' => '3509030008'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Dukuhmencek', 'kode_desa' => '3509030009'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Glagahwero', 'kode_desa' => '3509030010'],
            ['id_kecamatan' => 4, 'nama_desa' => 'Paleran', 'kode_desa' => '3509030011'],

            // Kecamatan Ambulu (5)
            ['id_kecamatan' => 5, 'nama_desa' => 'Ambulu', 'kode_desa' => '3509040001'],
            ['id_kecamatan' => 5, 'nama_desa' => 'Andongsari', 'kode_desa' => '3509040002'],
            ['id_kecamatan' => 5, 'nama_desa' => 'Pontang', 'kode_desa' => '3509040003'],
            ['id_kecamatan' => 5, 'nama_desa' => 'Karang Semanding', 'kode_desa' => '3509040004'],
            ['id_kecamatan' => 5, 'nama_desa' => 'Sabrang', 'kode_desa' => '3509040005'],
            ['id_kecamatan' => 5, 'nama_desa' => 'Tegalsari', 'kode_desa' => '3509040006'],
            ['id_kecamatan' => 5, 'nama_desa' => 'Sumberjeruk', 'kode_desa' => '3509040007'],
            ['id_kecamatan' => 5, 'nama_desa' => 'Sumberkalong', 'kode_desa' => '3509040008'],
            ['id_kecamatan' => 5, 'nama_desa' => 'Sumbersari', 'kode_desa' => '3509040009'],
            ['id_kecamatan' => 5, 'nama_desa' => 'Sumbertengah', 'kode_desa' => '3509040010'],

            // Kecamatan Tempurejo (6)
            ['id_kecamatan' => 6, 'nama_desa' => 'Tempurejo', 'kode_desa' => '3509050001'],
            ['id_kecamatan' => 6, 'nama_desa' => 'Sanenrejo', 'kode_desa' => '3509050002'],
            ['id_kecamatan' => 6, 'nama_desa' => 'Pondoknongko', 'kode_desa' => '3509050003'],
            ['id_kecamatan' => 6, 'nama_desa' => 'Sidodadi', 'kode_desa' => '3509050004'],
            ['id_kecamatan' => 6, 'nama_desa' => 'Wirowongso', 'kode_desa' => '3509050005'],
            ['id_kecamatan' => 6, 'nama_desa' => 'Alas Sumur Lor', 'kode_desa' => '3509050006'],
            ['id_kecamatan' => 6, 'nama_desa' => 'Alas Sumur Kidul', 'kode_desa' => '3509050007'],

            // Kecamatan Silo (7)
            ['id_kecamatan' => 7, 'nama_desa' => 'Silo', 'kode_desa' => '3509060001'],
            ['id_kecamatan' => 7, 'nama_desa' => 'Mulyorejo', 'kode_desa' => '3509060002'],
            ['id_kecamatan' => 7, 'nama_desa' => 'Harjomulyo', 'kode_desa' => '3509060003'],
            ['id_kecamatan' => 7, 'nama_desa' => 'Sumberlesung', 'kode_desa' => '3509060004'],
            ['id_kecamatan' => 7, 'nama_desa' => 'Sidomulyo', 'kode_desa' => '3509060005'],
            ['id_kecamatan' => 7, 'nama_desa' => 'Garahan', 'kode_desa' => '3509060006'],
            ['id_kecamatan' => 7, 'nama_desa' => 'Kaliglagah', 'kode_desa' => '3509060007'],
            ['id_kecamatan' => 7, 'nama_desa' => 'Pace', 'kode_desa' => '3509060008'],

            // Kecamatan Mayang (8)
            ['id_kecamatan' => 8, 'nama_desa' => 'Tegalwaru', 'kode_desa' => '3509070001'],
            ['id_kecamatan' => 8, 'nama_desa' => 'Seputih', 'kode_desa' => '3509070002'],
            ['id_kecamatan' => 8, 'nama_desa' => 'Sumberwaru', 'kode_desa' => '3509070003'],
            ['id_kecamatan' => 8, 'nama_desa' => 'Tegalrejo', 'kode_desa' => '3509070004'],
            ['id_kecamatan' => 8, 'nama_desa' => 'Sidomukti', 'kode_desa' => '3509070005'],
            ['id_kecamatan' => 8, 'nama_desa' => 'Sumbersari', 'kode_desa' => '3509070006'],
            ['id_kecamatan' => 8, 'nama_desa' => 'Sukorejo', 'kode_desa' => '3509070007'],
            ['id_kecamatan' => 8, 'nama_desa' => 'Tanjungsari', 'kode_desa' => '3509070008'],
            ['id_kecamatan' => 8, 'nama_desa' => 'Tegalwangi', 'kode_desa' => '3509070009'],

            // Kecamatan Mumbulsari (9)
            ['id_kecamatan' => 9, 'nama_desa' => 'Mumbulsari', 'kode_desa' => '3509080001'],
            ['id_kecamatan' => 9, 'nama_desa' => 'Lampeji', 'kode_desa' => '3509080002'],
            ['id_kecamatan' => 9, 'nama_desa' => 'Karangkedawung', 'kode_desa' => '3509080003'],
            ['id_kecamatan' => 9, 'nama_desa' => 'Karangpaiton', 'kode_desa' => '3509080004'],
            ['id_kecamatan' => 9, 'nama_desa' => 'Gumelar', 'kode_desa' => '3509080005'],
            ['id_kecamatan' => 9, 'nama_desa' => 'Emplasemen', 'kode_desa' => '3509080006'],
            ['id_kecamatan' => 9, 'nama_desa' => 'Suco', 'kode_desa' => '3509080007'],
            ['id_kecamatan' => 9, 'nama_desa' => 'Kaliasri', 'kode_desa' => '3509080008'],
            ['id_kecamatan' => 9, 'nama_desa' => 'Tamansari', 'kode_desa' => '3509080009'],

            // Kecamatan Jenggawah (10)
            ['id_kecamatan' => 10, 'nama_desa' => 'Jenggawah', 'kode_desa' => '3509081001'],
            ['id_kecamatan' => 10, 'nama_desa' => 'Jatimulyo', 'kode_desa' => '3509081002'],
            ['id_kecamatan' => 10, 'nama_desa' => 'Cangkring', 'kode_desa' => '3509081003'],
            ['id_kecamatan' => 10, 'nama_desa' => 'Kertonegoro', 'kode_desa' => '3509081004'],
            ['id_kecamatan' => 10, 'nama_desa' => 'Karangbendo', 'kode_desa' => '3509081005'],
            ['id_kecamatan' => 10, 'nama_desa' => 'Suger Kidul', 'kode_desa' => '3509081006'],
            ['id_kecamatan' => 10, 'nama_desa' => 'Suger Lor', 'kode_desa' => '3509081007'],
            ['id_kecamatan' => 10, 'nama_desa' => 'Mangli', 'kode_desa' => '3509081008'],
            ['id_kecamatan' => 10, 'nama_desa' => 'Kasiyan Timur', 'kode_desa' => '3509081009'],
            ['id_kecamatan' => 10, 'nama_desa' => 'Kasiyan', 'kode_desa' => '3509081010'],

            // Kecamatan Ajung (11)
            ['id_kecamatan' => 11, 'nama_desa' => 'Ajung', 'kode_desa' => '3509090001'],
            ['id_kecamatan' => 11, 'nama_desa' => 'Pancakarya', 'kode_desa' => '3509090002'],
            ['id_kecamatan' => 11, 'nama_desa' => 'Klompangan', 'kode_desa' => '3509090003'],
            ['id_kecamatan' => 11, 'nama_desa' => 'Rowo Indah', 'kode_desa' => '3509090004'],
            ['id_kecamatan' => 11, 'nama_desa' => 'Wirolegi', 'kode_desa' => '3509090005'],
            ['id_kecamatan' => 11, 'nama_desa' => 'Kamal', 'kode_desa' => '3509090006'],
            ['id_kecamatan' => 11, 'nama_desa' => 'Sukamakmur', 'kode_desa' => '3509090007'],
            ['id_kecamatan' => 11, 'nama_desa' => 'Pancur', 'kode_desa' => '3509090008'],
            ['id_kecamatan' => 11, 'nama_desa' => 'Curahdami', 'kode_desa' => '3509090009'],

            // Kecamatan Rambipuji (12)
            ['id_kecamatan' => 12, 'nama_desa' => 'Rambipuji', 'kode_desa' => '3509100001'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Rambigundam', 'kode_desa' => '3509100002'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Karangbayat', 'kode_desa' => '3509100003'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Rowotamtu', 'kode_desa' => '3509100004'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Karangkedawung', 'kode_desa' => '3509100005'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Andongrejo', 'kode_desa' => '3509100006'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Langkap', 'kode_desa' => '3509100007'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Mojosari', 'kode_desa' => '3509100008'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Karangharjo', 'kode_desa' => '3509100009'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Sabrang', 'kode_desa' => '3509100010'],
            ['id_kecamatan' => 12, 'nama_desa' => 'Gugut', 'kode_desa' => '3509100011'],

            // Kecamatan Balung (13)
            ['id_kecamatan' => 13, 'nama_desa' => 'Balung Kulon', 'kode_desa' => '3509101001'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Balung Lor', 'kode_desa' => '3509101002'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Balung Kidul', 'kode_desa' => '3509101003'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Tanjung', 'kode_desa' => '3509101004'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Karangduren', 'kode_desa' => '3509101005'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Curahnongko', 'kode_desa' => '3509101006'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Sukorejo', 'kode_desa' => '3509101007'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Sumberejo', 'kode_desa' => '3509101008'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Gumelar', 'kode_desa' => '3509101009'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Sobo', 'kode_desa' => '3509101010'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Bagon', 'kode_desa' => '3509101011'],
            ['id_kecamatan' => 13, 'nama_desa' => 'Sumberbulus', 'kode_desa' => '3509101012'],

            // Kecamatan Umbulsari (14)
            ['id_kecamatan' => 14, 'nama_desa' => 'Umbulsari', 'kode_desa' => '3509102001'],
            ['id_kecamatan' => 14, 'nama_desa' => 'Wringinagung', 'kode_desa' => '3509102002'],
            ['id_kecamatan' => 14, 'nama_desa' => 'Gadingrejo', 'kode_desa' => '3509102003'],
            ['id_kecamatan' => 14, 'nama_desa' => 'Wonoasri', 'kode_desa' => '3509102004'],
            ['id_kecamatan' => 14, 'nama_desa' => 'Karanganyar', 'kode_desa' => '3509102005'],
            ['id_kecamatan' => 14, 'nama_desa' => 'Pondokrejo', 'kode_desa' => '3509102006'],
            ['id_kecamatan' => 14, 'nama_desa' => 'Paleran', 'kode_desa' => '3509102007'],
            ['id_kecamatan' => 14, 'nama_desa' => 'Kemiri', 'kode_desa' => '3509102008'],
            ['id_kecamatan' => 14, 'nama_desa' => 'Tanjungrejo', 'kode_desa' => '3509102009'],

            // Kecamatan Semboro (15)
            ['id_kecamatan' => 15, 'nama_desa' => 'Semboro', 'kode_desa' => '3509110001'],
            ['id_kecamatan' => 15, 'nama_desa' => 'Pondokjoyo', 'kode_desa' => '3509110002'],
            ['id_kecamatan' => 15, 'nama_desa' => 'Rejoagung', 'kode_desa' => '3509110003'],
            ['id_kecamatan' => 15, 'nama_desa' => 'Pringgowirawan', 'kode_desa' => '3509110004'],
            ['id_kecamatan' => 15, 'nama_desa' => 'Pondoksalam', 'kode_desa' => '3509110005'],
            ['id_kecamatan' => 15, 'nama_desa' => 'Sidomulyo', 'kode_desa' => '3509110006'],
            ['id_kecamatan' => 15, 'nama_desa' => 'Tanggul Kulon', 'kode_desa' => '3509110007'],
            ['id_kecamatan' => 15, 'nama_desa' => 'Sumbersalak', 'kode_desa' => '3509110008'],
            ['id_kecamatan' => 15, 'nama_desa' => 'Sukorejo', 'kode_desa' => '3509110009'],

            // Kecamatan Jombang (16)
            ['id_kecamatan' => 16, 'nama_desa' => 'Jombang', 'kode_desa' => '3509111001'],
            ['id_kecamatan' => 16, 'nama_desa' => 'Kaliwining', 'kode_desa' => '3509111002'],
            ['id_kecamatan' => 16, 'nama_desa' => 'Kepanjen', 'kode_desa' => '3509111003'],
            ['id_kecamatan' => 16, 'nama_desa' => 'Wirowongso', 'kode_desa' => '3509111004'],
            ['id_kecamatan' => 16, 'nama_desa' => 'Kemuninglor', 'kode_desa' => '3509111005'],
            ['id_kecamatan' => 16, 'nama_desa' => 'Lembengan', 'kode_desa' => '3509111006'],
            ['id_kecamatan' => 16, 'nama_desa' => 'Rowosari', 'kode_desa' => '3509111007'],
            ['id_kecamatan' => 16, 'nama_desa' => 'Keting', 'kode_desa' => '3509111008'],
            ['id_kecamatan' => 16, 'nama_desa' => 'Karang Anyar', 'kode_desa' => '3509111009'],
            ['id_kecamatan' => 16, 'nama_desa' => 'Suco', 'kode_desa' => '3509111010'],

            // Kecamatan Tanggul (17)
            ['id_kecamatan' => 17, 'nama_desa' => 'Tanggul Wetan', 'kode_desa' => '3509120001'],
            ['id_kecamatan' => 17, 'nama_desa' => 'Tanggul Kulon', 'kode_desa' => '3509120002'],
            ['id_kecamatan' => 17, 'nama_desa' => 'Klatakan', 'kode_desa' => '3509120003'],
            ['id_kecamatan' => 17, 'nama_desa' => 'Darungan', 'kode_desa' => '3509120004'],
            ['id_kecamatan' => 17, 'nama_desa' => 'Kademangan', 'kode_desa' => '3509120005'],
            ['id_kecamatan' => 17, 'nama_desa' => 'Mangli', 'kode_desa' => '3509120006'],
            ['id_kecamatan' => 17, 'nama_desa' => 'Selodakon', 'kode_desa' => '3509120007'],
            ['id_kecamatan' => 17, 'nama_desa' => 'Patemon', 'kode_desa' => '3509120008'],
            ['id_kecamatan' => 17, 'nama_desa' => 'Jayanegara', 'kode_desa' => '3509120009'],

            // Kecamatan Bangsalsari (18)
            ['id_kecamatan' => 18, 'nama_desa' => 'Bangsalsari', 'kode_desa' => '3509121001'],
            ['id_kecamatan' => 18, 'nama_desa' => 'Curahmalang', 'kode_desa' => '3509121002'],
            ['id_kecamatan' => 18, 'nama_desa' => 'Tisnogambar', 'kode_desa' => '3509121003'],
            ['id_kecamatan' => 18, 'nama_desa' => 'Petung', 'kode_desa' => '3509121004'],
            ['id_kecamatan' => 18, 'nama_desa' => 'Badean', 'kode_desa' => '3509121005'],
            ['id_kecamatan' => 18, 'nama_desa' => 'Bagorejo', 'kode_desa' => '3509121006'],
            ['id_kecamatan' => 18, 'nama_desa' => 'Karangbendo', 'kode_desa' => '3509121007'],
            ['id_kecamatan' => 18, 'nama_desa' => 'Pakusari', 'kode_desa' => '3509121008'],

            // Kecamatan Panti (19)
            ['id_kecamatan' => 19, 'nama_desa' => 'Panti', 'kode_desa' => '3509130001'],
            ['id_kecamatan' => 19, 'nama_desa' => 'Kemiri', 'kode_desa' => '3509130002'],
            ['id_kecamatan' => 19, 'nama_desa' => 'Suci', 'kode_desa' => '3509130003'],
            ['id_kecamatan' => 19, 'nama_desa' => 'Seruni', 'kode_desa' => '3509130004'],
            ['id_kecamatan' => 19, 'nama_desa' => 'Pakusari', 'kode_desa' => '3509130005'],
            ['id_kecamatan' => 19, 'nama_desa' => 'Jamintoro', 'kode_desa' => '3509130006'],
            ['id_kecamatan' => 19, 'nama_desa' => 'Karangharjo', 'kode_desa' => '3509130007'],
            ['id_kecamatan' => 19, 'nama_desa' => 'Sukokerto', 'kode_desa' => '3509130008'],
            ['id_kecamatan' => 19, 'nama_desa' => 'Jatiroto', 'kode_desa' => '3509130009'],

            // Kecamatan Sukorambi (20)
            ['id_kecamatan' => 20, 'nama_desa' => 'Sukorambi', 'kode_desa' => '3509140001'],
            ['id_kecamatan' => 20, 'nama_desa' => 'Karang Pring', 'kode_desa' => '3509140002'],
            ['id_kecamatan' => 20, 'nama_desa' => 'Cangkring', 'kode_desa' => '3509140003'],
            ['id_kecamatan' => 20, 'nama_desa' => 'Klungkung', 'kode_desa' => '3509140004'],
            ['id_kecamatan' => 20, 'nama_desa' => 'Curahkalong', 'kode_desa' => '3509140005'],
            ['id_kecamatan' => 20, 'nama_desa' => 'Mrawan', 'kode_desa' => '3509140006'],
            ['id_kecamatan' => 20, 'nama_desa' => 'Sukosari', 'kode_desa' => '3509140007'],

            // Kecamatan Arjasa (21)
            ['id_kecamatan' => 21, 'nama_desa' => 'Arjasa', 'kode_desa' => '3509150001'],
            ['id_kecamatan' => 21, 'nama_desa' => 'Karang Anyar', 'kode_desa' => '3509150002'],
            ['id_kecamatan' => 21, 'nama_desa' => 'Candijati', 'kode_desa' => '3509150003'],
            ['id_kecamatan' => 21, 'nama_desa' => 'Biting', 'kode_desa' => '3509150004'],
            ['id_kecamatan' => 21, 'nama_desa' => 'Karangrejo', 'kode_desa' => '3509150005'],
            ['id_kecamatan' => 21, 'nama_desa' => 'Candijati Wetan', 'kode_desa' => '3509150006'],
            ['id_kecamatan' => 21, 'nama_desa' => 'Pakis', 'kode_desa' => '3509150007'],
            ['id_kecamatan' => 21, 'nama_desa' => 'Tanjungjember', 'kode_desa' => '3509150008'],

            // Kecamatan Pakusari (22)
            ['id_kecamatan' => 22, 'nama_desa' => 'Pakusari', 'kode_desa' => '3509160001'],
            ['id_kecamatan' => 22, 'nama_desa' => 'Kertosari', 'kode_desa' => '3509160002'],
            ['id_kecamatan' => 22, 'nama_desa' => 'Sukorejo', 'kode_desa' => '3509160003'],
            ['id_kecamatan' => 22, 'nama_desa' => 'Keting', 'kode_desa' => '3509160004'],
            ['id_kecamatan' => 22, 'nama_desa' => 'Slateng', 'kode_desa' => '3509160005'],
            ['id_kecamatan' => 22, 'nama_desa' => 'Gumelar', 'kode_desa' => '3509160006'],
            ['id_kecamatan' => 22, 'nama_desa' => 'Jomerto', 'kode_desa' => '3509160007'],

            // Kecamatan Kalisat (23)
            ['id_kecamatan' => 23, 'nama_desa' => 'Kalisat', 'kode_desa' => '3509170001'],
            ['id_kecamatan' => 23, 'nama_desa' => 'Gambiran', 'kode_desa' => '3509170002'],
            ['id_kecamatan' => 23, 'nama_desa' => 'Ajung', 'kode_desa' => '3509170003'],
            ['id_kecamatan' => 23, 'nama_desa' => 'Sebanen', 'kode_desa' => '3509170004'],
            ['id_kecamatan' => 23, 'nama_desa' => 'Gumuksari', 'kode_desa' => '3509170005'],
            ['id_kecamatan' => 23, 'nama_desa' => 'Plalangan', 'kode_desa' => '3509170006'],
            ['id_kecamatan' => 23, 'nama_desa' => 'Jamintoro', 'kode_desa' => '3509170007'],
            ['id_kecamatan' => 23, 'nama_desa' => 'Sumberjeruk', 'kode_desa' => '3509170008'],
            ['id_kecamatan' => 23, 'nama_desa' => 'Kaliglagah', 'kode_desa' => '3509170009'],

            // Kecamatan Ledokombo (24)
            ['id_kecamatan' => 24, 'nama_desa' => 'Ledokombo', 'kode_desa' => '3509180001'],
            ['id_kecamatan' => 24, 'nama_desa' => 'Slateng', 'kode_desa' => '3509180002'],
            ['id_kecamatan' => 24, 'nama_desa' => 'Karang Paiton', 'kode_desa' => '3509180003'],
            ['id_kecamatan' => 24, 'nama_desa' => 'Sukosari Lor', 'kode_desa' => '3509180004'],
            ['id_kecamatan' => 24, 'nama_desa' => 'Sukosari Kidul', 'kode_desa' => '3509180005'],
            ['id_kecamatan' => 24, 'nama_desa' => 'Sumberan', 'kode_desa' => '3509180006'],
            ['id_kecamatan' => 24, 'nama_desa' => 'Curahnongko', 'kode_desa' => '3509180007'],
            ['id_kecamatan' => 24, 'nama_desa' => 'Jambearum', 'kode_desa' => '3509180008'],

            // Kecamatan Sumberjambe (25)
            ['id_kecamatan' => 25, 'nama_desa' => 'Sumberjambe', 'kode_desa' => '3509190001'],
            ['id_kecamatan' => 25, 'nama_desa' => 'Karangsari', 'kode_desa' => '3509190002'],
            ['id_kecamatan' => 25, 'nama_desa' => 'Rowosari', 'kode_desa' => '3509190003'],
            ['id_kecamatan' => 25, 'nama_desa' => 'Sucopangepok', 'kode_desa' => '3509190004'],
            ['id_kecamatan' => 25, 'nama_desa' => 'Jambesari', 'kode_desa' => '3509190005'],
            ['id_kecamatan' => 25, 'nama_desa' => 'Gunungmalang', 'kode_desa' => '3509190006'],

            // Kecamatan Sukowono (26)
            ['id_kecamatan' => 26, 'nama_desa' => 'Sukowono', 'kode_desa' => '3509200001'],
            ['id_kecamatan' => 26, 'nama_desa' => 'Mojosari', 'kode_desa' => '3509200002'],
            ['id_kecamatan' => 26, 'nama_desa' => 'Dawuhan', 'kode_desa' => '3509200003'],
            ['id_kecamatan' => 26, 'nama_desa' => 'Sukokerto', 'kode_desa' => '3509200004'],
            ['id_kecamatan' => 26, 'nama_desa' => 'Patrang', 'kode_desa' => '3509200005'],
            ['id_kecamatan' => 26, 'nama_desa' => 'Curahmalang', 'kode_desa' => '3509200006'],
            ['id_kecamatan' => 26, 'nama_desa' => 'Losari', 'kode_desa' => '3509200007'],
            ['id_kecamatan' => 26, 'nama_desa' => 'Pocangan', 'kode_desa' => '3509200008'],
            ['id_kecamatan' => 26, 'nama_desa' => 'Harjomulyo', 'kode_desa' => '3509200009'],

            // Kecamatan Jelbuk (27)
            ['id_kecamatan' => 27, 'nama_desa' => 'Jelbuk', 'kode_desa' => '3509210001'],
            ['id_kecamatan' => 27, 'nama_desa' => 'Sukowiryo', 'kode_desa' => '3509210002'],
            ['id_kecamatan' => 27, 'nama_desa' => 'Sugerkidul', 'kode_desa' => '3509210003'],
            ['id_kecamatan' => 27, 'nama_desa' => 'Sugerlor', 'kode_desa' => '3509210004'],
            ['id_kecamatan' => 27, 'nama_desa' => 'Badean', 'kode_desa' => '3509210005'],

            // Kecamatan Kaliwates (28)
            ['id_kecamatan' => 28, 'nama_desa' => 'Kaliwates', 'kode_desa' => '3509220001'],
            ['id_kecamatan' => 28, 'nama_desa' => 'Kepatihan', 'kode_desa' => '3509220002'],
            ['id_kecamatan' => 28, 'nama_desa' => 'Sempusari', 'kode_desa' => '3509220003'],
            ['id_kecamatan' => 28, 'nama_desa' => 'Mangli', 'kode_desa' => '3509220004'],
            ['id_kecamatan' => 28, 'nama_desa' => 'Tegal Gede', 'kode_desa' => '3509220005'],
            ['id_kecamatan' => 28, 'nama_desa' => 'Jember Kidul', 'kode_desa' => '3509220006'],
            ['id_kecamatan' => 28, 'nama_desa' => 'Jember Lor', 'kode_desa' => '3509220007'],

            // Kecamatan Sumbersari (29)
            ['id_kecamatan' => 29, 'nama_desa' => 'Sumbersari', 'kode_desa' => '3509230001'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Wirolegi', 'kode_desa' => '3509230002'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Antirogo', 'kode_desa' => '3509230003'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Tegalgede', 'kode_desa' => '3509230004'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Karang Paiton', 'kode_desa' => '3509230005'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Kranjingan', 'kode_desa' => '3509230006'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Rowotamtu', 'kode_desa' => '3509230007'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Kebonsari', 'kode_desa' => '3509230008'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Kaliwates', 'kode_desa' => '3509230009'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Slawu', 'kode_desa' => '3509230010'],
            ['id_kecamatan' => 29, 'nama_desa' => 'Banjarsengon', 'kode_desa' => '3509230011'],

            // Kecamatan Patrang (30)
            ['id_kecamatan' => 30, 'nama_desa' => 'Patrang', 'kode_desa' => '3509240001'],
            ['id_kecamatan' => 30, 'nama_desa' => 'Jumerto', 'kode_desa' => '3509240002'],
            ['id_kecamatan' => 30, 'nama_desa' => 'Baratan', 'kode_desa' => '3509240003'],
            ['id_kecamatan' => 30, 'nama_desa' => 'Slawu', 'kode_desa' => '3509240004'],
            ['id_kecamatan' => 30, 'nama_desa' => 'Gebang', 'kode_desa' => '3509240005'],
            ['id_kecamatan' => 30, 'nama_desa' => 'Bintoro', 'kode_desa' => '3509240006'],
            ['id_kecamatan' => 30, 'nama_desa' => 'Jember Kidul', 'kode_desa' => '3509240007'],
            ['id_kecamatan' => 30, 'nama_desa' => 'Banjarsengon', 'kode_desa' => '3509240008'],

            // Kecamatan Mangli (31)
            ['id_kecamatan' => 31, 'nama_desa' => 'Mangli', 'kode_desa' => '3509250001'],
            ['id_kecamatan' => 31, 'nama_desa' => 'Karangrejo', 'kode_desa' => '3509250002'],
            ['id_kecamatan' => 31, 'nama_desa' => 'Sumberbaru', 'kode_desa' => '3509250003'],
            ['id_kecamatan' => 31, 'nama_desa' => 'Kelurahan Mangli', 'kode_desa' => '3509250004'],
        ];

        foreach ($desas as $desa) {
            DB::table('master_desas')->insert([
                'id_kecamatan' => $desa['id_kecamatan'],
                'nama_desa' => $desa['nama_desa'],
                'kode_desa' => $desa['kode_desa'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✓ ' . count($desas) . ' Desa/Kelurahan Kabupaten Jember berhasil di-seed');
    }
}
