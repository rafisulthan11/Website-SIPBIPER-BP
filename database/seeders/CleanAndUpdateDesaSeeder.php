<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use Illuminate\Support\Facades\DB;

class CleanAndUpdateDesaSeeder extends Seeder
{
    public function run(): void
    {
        // Dapatkan mapping kecamatan
        $kecamatanMap = MasterKecamatan::pluck('id_kecamatan', 'nama_kecamatan')->toArray();

        // Desa data sesuai gambar yang akurat
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

        // Untuk setiap kecamatan, update desa
        $totalDesa = 0;
        $updated = 0;
        $created = 0;
        
        foreach ($desaData as $kecamatanNama => $desaList) {
            if (isset($kecamatanMap[$kecamatanNama])) {
                $idKecamatan = $kecamatanMap[$kecamatanNama];
                
                // Hapus desa yang tidak ada di list baru untuk kecamatan ini
                $existingDesas = MasterDesa::where('id_kecamatan', $idKecamatan)
                    ->whereNotIn('nama_desa', $desaList)
                    ->delete();
                
                // Update atau create desa yang sesuai list
                foreach ($desaList as $namaDesa) {
                    $desa = MasterDesa::updateOrCreate(
                        [
                            'nama_desa' => $namaDesa,
                            'id_kecamatan' => $idKecamatan
                        ]
                    );
                    
                    if ($desa->wasRecentlyCreated) {
                        $created++;
                    } else {
                        $updated++;
                    }
                    $totalDesa++;
                }
            }
        }

        echo "Data desa berhasil dibersihkan dan diupdate!\n";
        echo "Total kecamatan: " . count($kecamatanMap) . "\n";
        echo "Total desa: " . $totalDesa . "\n";
        echo "Desa baru: " . $created . "\n";
        echo "Desa diupdate: " . $updated . "\n";
    }
}
