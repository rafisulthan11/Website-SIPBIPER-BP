<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;

class UpdateKecamatanDesaSeeder extends Seeder
{
    public function run(): void
    {
        // Update kode kecamatan sesuai gambar
        $kecamatans = [
            ['kode' => '35.09.17', 'nama' => 'Ajung'],
            ['kode' => '35.09.12', 'nama' => 'Ambulu'],
            ['kode' => '35.09.22', 'nama' => 'Arjasa'],
            ['kode' => '35.09.09', 'nama' => 'Bangsalsari'],
            ['kode' => '35.09.10', 'nama' => 'Balung'],
            ['kode' => '35.09.04', 'nama' => 'Gumukmas'],
            ['kode' => '35.09.25', 'nama' => 'Jelbuk'],
            ['kode' => '35.09.16', 'nama' => 'Jenggawah'],
            ['kode' => '35.09.01', 'nama' => 'Jombang'],
            ['kode' => '35.09.27', 'nama' => 'Kalisat'],
            ['kode' => '35.09.19', 'nama' => 'Kaliwates'],
            ['kode' => '35.09.02', 'nama' => 'Kencong'],
            ['kode' => '35.09.28', 'nama' => 'Ledokombo'],
            ['kode' => '35.09.26', 'nama' => 'Mayang'],
            ['kode' => '35.09.23', 'nama' => 'Mumbulsari'],
            ['kode' => '35.09.14', 'nama' => 'Panti'],
            ['kode' => '35.09.24', 'nama' => 'Pakusari'],
            ['kode' => '35.09.20', 'nama' => 'Patrang'],
            ['kode' => '35.09.08', 'nama' => 'Puger'],
            ['kode' => '35.09.13', 'nama' => 'Rambipuji'],
            ['kode' => '35.09.07', 'nama' => 'Semboro'],
            ['kode' => '35.09.30', 'nama' => 'Silo'],
            ['kode' => '35.09.15', 'nama' => 'Sukorambi'],
            ['kode' => '35.09.29', 'nama' => 'Sukowono'],
            ['kode' => '35.09.03', 'nama' => 'Sumberbaru'],
            ['kode' => '35.09.31', 'nama' => 'Sumberjambe'],
            ['kode' => '35.09.21', 'nama' => 'Sumbersari'],
            ['kode' => '35.09.06', 'nama' => 'Tanggul'],
            ['kode' => '35.09.18', 'nama' => 'Tempurejo'],
            ['kode' => '35.09.05', 'nama' => 'Umbulsari'],
            ['kode' => '35.09.11', 'nama' => 'Wuluhan'],
        ];

        foreach ($kecamatans as $kec) {
            MasterKecamatan::updateOrCreate(
                ['nama_kecamatan' => $kec['nama']],
                ['kode_kecamatan' => $kec['kode']]
            );
        }

        $kecamatanMap = MasterKecamatan::pluck('id_kecamatan', 'nama_kecamatan')->toArray();

        // Desa data sesuai gambar (hanya nama desa)
        $desaData = [
            'Ajung' => ['Ajung', 'Klompangan', 'Mangaran', 'Pancakarya', 'Rowoindah', 'Sukamakmur', 'Wirowongso'],
            'Ambulu' => ['Ambulu', 'Andongsari', 'Karang Anyar', 'Pontang', 'Sabrang', 'Sumberejo', 'Tegalsari'],
            'Arjasa' => ['Arjasa', 'Biting', 'Candijati', 'Darsono', 'Kamal', 'Kemuning Lor'],
            'Bangsalsari' => ['Badean', 'Bangsalsari', 'Banjarsari', 'Curahkalong', 'Gambirono', 'Karangsono', 'Langkap', 'Petung', 'Sukorejo', 'Tisnogambar', 'Tugusari'],
            'Balung' => ['Balung Kidul', 'Balung Kulon', 'Balung Lor', 'Curahlele', 'Gumelar', 'Karangduren', 'Karang Semanding', 'Tutul'],
            'Gumukmas' => ['Bagorejo', 'Gumukmas', 'Karangrejo', 'Kepanjen', 'Mayangan', 'Menampu', 'Purwoasri', 'Tembokrejo'],
            'Jelbuk' => ['Jelbuk', 'Panduman', 'Sucopangepok', 'Sugerkidul', 'Sukojember', 'Sukowiryo'],
            'Jenggawah' => ['Cangkring', 'Jatimulyo', 'Jatisari', 'Jenggawah', 'Kemuningsari Kidul', 'Kertonegoro', 'Sruni', 'Wonojati'],
            'Jombang' => ['Jombang', 'Keting', 'Ngampelrejo', 'Padomasan', 'Sarimulyo', 'Wringinagung'],
            'Kalisat' => ['Ajung', 'Gambiran', 'Glagahwero', 'Gumuksari', 'Kalisat', 'Patempuran', 'Plalangan', 'Sebanen', 'Sukoreno', 'Sumberjeruk', 'Sumberkalong', 'Sumberketempa'],
            'Kaliwates' => ['Jember Kidul', 'Kaliwates', 'Kebon Agung', 'Kepatihan', 'Mangli', 'Sempusari', 'Tegal Besar'],
            'Kencong' => ['Cakru', 'Kencong', 'Kraton', 'Paseban', 'Wonorejo'],
            'Ledokombo' => ['Karangpaiton', 'Ledokombo', 'Lembengan', 'Slateng', 'Sukogidri', 'Sumberanget', 'Sumberbulus', 'Sumberlesung', 'Sumbersalak', 'Suren'],
            'Mayang' => ['Mayang', 'Mrawan', 'Seputih', 'Sidomukti', 'Sumberkejayan', 'Tegalwaru', 'Tegalrejo'],
            'Mumbulsari' => ['Karang Kedawung', 'Kawangrejo', 'Lampeji', 'Lengkong', 'Mumbulsari', 'Suco', 'Tamansari'],
            'Panti' => ['Glagahwero', 'Kemiri', 'Kemuningsari Lor', 'Pakis', 'Panti', 'Serut', 'Suci'],
            'Pakusari' => ['Bedadung', 'Jatian', 'Kertosari', 'Pakusari', 'Patemon', 'Subo', 'Sumberpinang'],
            'Patrang' => ['Banjarsengon', 'Baratan', 'Bintoro', 'Gebang', 'Jemberlor', 'Jumerto', 'Patrang', 'Slawu'],
            'Puger' => ['Bagon', 'Grenden', 'Jambearum', 'Kasiyan', 'Kasiyan Timur', 'Mlokorejo', 'Mojomulyo', 'Mojosari', 'Puger Kulon', 'Puger Wetan', 'Wonosari', 'Wringintelu'],
            'Rambipuji' => ['Curahmalang', 'Gugut', 'Kaliwining', 'Nogosari', 'Pecoro', 'Rambigundam', 'Rambipuji', 'Rowotamtu'],
            'Semboro' => ['Pondokdalem', 'Pondokjoyo', 'Rejoagung', 'Semboro', 'Sidomekar', 'Sidomulyo'],
            'Silo' => ['Garahan', 'Harjomulyo', 'Karangharjo', 'Mulyorejo', 'Pace', 'Sempolan', 'Sidomulyo', 'Silo', 'Sumberjati'],
            'Sukorambi' => ['Dukuhmencek', 'Jubung', 'Karangpring', 'Klungkung', 'Sukorambi'],
            'Sukowono' => ['Arjasa', 'Balet Baru', 'Dawuhanmangli', 'Mojogemi', 'Pocangan', 'Sukokerto', 'Sukorejo', 'Sukosari', 'Sukowono', 'Sumberwringin', 'Sumberdanti', 'Sumberwaru'],
            'Sumberbaru' => ['Gelang', 'Jambesari', 'Jamintoro', 'Jatiroto', 'Kaliglagah', 'Karangbayat', 'Pringgowirawan', 'Rowotengah', 'Sumberagung', 'Yosorati'],
            'Sumberjambe' => ['Cumedak', 'Gunungmalang', 'Jambearum', 'Plerean', 'Pringgondani', 'Randuagung', 'Rowosari', 'Sumberjambe', 'Sumberpakem'],
            'Sumbersari' => ['Antirogo', 'Karangrejo', 'Kebonsari', 'Kranjingan', 'Sumbersari', 'Tegalgede', 'Wirolegi'],
            'Tanggul' => ['Darungan', 'Klatakan', 'Kramat Sukoharjo', 'Manggisan', 'Patemon', 'Selodakon', 'Tanggul Kulon', 'Tanggul Wetan'],
            'Tempurejo' => ['Andongrejo', 'Curahnongko', 'Curahtakir', 'Pondokrejo', 'Sidodadi', 'Sanenrejo', 'Tempurejo', 'Wonoasri'],
            'Umbulsari' => ['Gadingrejo', 'Gunungsari', 'Mundurejo', 'Paleran', 'Sidorejo', 'Sukoreno', 'Tanjungsari', 'Tegalwangi', 'Umbulrejo', 'Umbulsari'],
            'Wuluhan' => ['Ampel', 'Dukuhdempok', 'Glundengan', 'Kesilir', 'Lojejer', 'Tamansari', 'Tanjungrejo'],
        ];

        foreach ($desaData as $kecamatanNama => $desaList) {
            if (isset($kecamatanMap[$kecamatanNama])) {
                $idKecamatan = $kecamatanMap[$kecamatanNama];
                
                foreach ($desaList as $namaDesa) {
                    MasterDesa::updateOrCreate(
                        [
                            'nama_desa' => $namaDesa,
                            'id_kecamatan' => $idKecamatan
                        ]
                    );
                }
            }
        }

        echo "Data kecamatan dan desa berhasil diupdate!\n";
    }
}
