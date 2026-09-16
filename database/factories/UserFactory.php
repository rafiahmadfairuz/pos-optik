<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    private static array $customers = [

        ['Ahmad Pratama','ahmad.pratama@gmail.com','081234567801','Jl. Melati No. 12','1995-03-12','male'],
        ['Budi Santoso','budi.santoso@gmail.com','081234567802','Jl. Mawar No. 5','1992-07-20','male'],
        ['Citra Lestari','citra.lestari@gmail.com','081234567803','Jl. Kenanga No. 8','1998-05-14','female'],
        ['Dimas Saputra','dimas.saputra@gmail.com','081234567804','Jl. Anggrek No. 15','1994-09-02','male'],
        ['Eka Putri','eka.putri@gmail.com','081234567805','Jl. Dahlia No. 7','1999-01-18','female'],
        ['Farhan Rizki','farhan.rizki@gmail.com','081234567806','Jl. Flamboyan No. 21','1997-11-23','male'],
        ['Gita Permata','gita.permata@gmail.com','081234567807','Jl. Cempaka No. 10','1996-06-30','female'],
        ['Hendra Wijaya','hendra.wijaya@gmail.com','081234567808','Jl. Teratai No. 3','1991-12-08','male'],
        ['Indah Sari','indah.sari@gmail.com','081234567809','Jl. Merpati No. 9','1998-08-11','female'],
        ['Joko Susilo','joko.susilo@gmail.com','081234567810','Jl. Garuda No. 18','1993-02-04','male'],

        ['Kevin Aditya','kevin.aditya@gmail.com','081234567811','Jl. Rajawali No. 6','1995-10-16','male'],
        ['Laras Ayu','laras.ayu@gmail.com','081234567812','Jl. Mangga No. 14','1999-04-10','female'],
        ['Muhammad Fikri','muhammad.fikri@gmail.com','081234567813','Jl. Rambutan No. 2','1994-01-29','male'],
        ['Nabila Zahra','nabila.zahra@gmail.com','081234567814','Jl. Durian No. 5','1997-07-01','female'],
        ['Oscar Prakoso','oscar.prakoso@gmail.com','081234567815','Jl. Apel No. 17','1996-09-09','male'],
        ['Putri Amelia','putri.amelia@gmail.com','081234567816','Jl. Jeruk No. 13','1998-12-21','female'],
        ['Qori Rahman','qori.rahman@gmail.com','081234567817','Jl. Nangka No. 8','1993-05-18','male'],
        ['Rina Marlina','rina.marlina@gmail.com','081234567818','Jl. Alpukat No. 4','1995-08-15','female'],
        ['Sandi Kurniawan','sandi.kurniawan@gmail.com','081234567819','Jl. Kamboja No. 11','1992-06-12','male'],
        ['Tia Novita','tia.novita@gmail.com','081234567820','Jl. Bougenville No. 9','1999-03-19','female'],

        ['Umar Hakim','umar.hakim@gmail.com','081234567821','Jl. Cemara No. 1','1994-10-22','male'],
        ['Vina Oktavia','vina.oktavia@gmail.com','081234567822','Jl. Pinus No. 16','1998-02-28','female'],
        ['Wahyu Nugroho','wahyu.nugroho@gmail.com','081234567823','Jl. Akasia No. 20','1993-11-13','male'],
        ['Xenia Putri','xenia.putri@gmail.com','081234567824','Jl. Sakura No. 6','1997-09-17','female'],
        ['Yoga Pratama','yoga.pratama@gmail.com','081234567825','Jl. Angsana No. 15','1995-05-07','male'],
        ['Zahra Nabila','zahra.nabila@gmail.com','081234567826','Jl. Kenari No. 12','1999-07-27','female'],
        ['Agus Setiawan','agus.setiawan@gmail.com','081234567827','Jl. Wijaya No. 19','1992-01-11','male'],
        ['Bella Savitri','bella.savitri@gmail.com','081234567828','Jl. Diponegoro No. 3','1998-04-05','female'],
        ['Cahyo Wibowo','cahyo.wibowo@gmail.com','081234567829','Jl. Sudirman No. 8','1996-06-14','male'],
        ['Dewi Anggraini','dewi.anggraini@gmail.com','081234567830','Jl. Kartini No. 22','1997-10-20','female'],

        ['Erwin Saputra','erwin.saputra@gmail.com','081234567831','Jl. Veteran No. 5','1991-08-08','male'],
        ['Fitri Aulia','fitri.aulia@gmail.com','081234567832','Jl. Gajah Mada No. 7','1999-11-30','female'],
        ['Galih Ramadhan','galih.ramadhan@gmail.com','081234567833','Jl. Pattimura No. 14','1994-04-01','male'],
        ['Hani Wulandari','hani.wulandari@gmail.com','081234567834','Jl. Ahmad Yani No. 18','1996-02-15','female'],
        ['Ilham Maulana','ilham.maulana@gmail.com','081234567835','Jl. Hasanuddin No. 10','1995-12-09','male'],
        ['Jihan Safira','jihan.safira@gmail.com','081234567836','Jl. Veteran No. 23','1998-09-25','female'],
        ['Kurniawan Hadi','kurniawan.hadi@gmail.com','081234567837','Jl. Imam Bonjol No. 6','1992-03-27','male'],
        ['Linda Oktaviani','linda.oktaviani@gmail.com','081234567838','Jl. Cendana No. 11','1997-01-03','female'],
        ['Moch Rizal','moch.rizal@gmail.com','081234567839','Jl. Cemara No. 9','1993-07-18','male'],
        ['Nia Ramadhani','nia.ramadhani@gmail.com','081234567840','Jl. Beringin No. 13','1999-06-22','female'],

        ['Oki Prasetyo','oki.prasetyo@gmail.com','081234567841','Jl. Mahoni No. 4','1995-02-12','male'],
        ['Puspita Dewi','puspita.dewi@gmail.com','081234567842','Jl. Merdeka No. 16','1998-05-30','female'],
        ['Rendi Saputra','rendi.saputra@gmail.com','081234567843','Jl. Pahlawan No. 21','1994-10-01','male'],
        ['Salsa Azzahra','salsa.azzahra@gmail.com','081234567844','Jl. Kenanga No. 5','1999-03-08','female'],
        ['Taufik Hidayat','taufik.hidayat@gmail.com','081234567845','Jl. Kelinci No. 8','1991-12-15','male'],
        ['Ulfa Rahma','ulfa.rahma@gmail.com','081234567846','Jl. Elang No. 7','1996-08-19','female'],
        ['Vicky Pramana','vicky.pramana@gmail.com','081234567847','Jl. Merak No. 2','1995-09-09','male'],
        ['Winda Lestari','winda.lestari@gmail.com','081234567848','Jl. Nusa Indah No. 14','1998-01-20','female'],
        ['Yusuf Kurnia','yusuf.kurnia@gmail.com','081234567849','Jl. Cempa No. 17','1992-11-28','male'],
        ['Zidan Ramadhan','zidan.ramadhan@gmail.com','081234567850','Jl. Puspa No. 10','1997-06-06','male'],
    ];

    public function definition(): array
    {
        static $index = 0;

        $customer = self::$customers[$index++ % count(self::$customers)];

        return [
            'name' => $customer[0],
            'email' => $customer[1],
            'phone' => $customer[2],
            'alamat' => $customer[3],
            'umur' => $customer[4],
            'gender' => $customer[5],
            'cabang_id' => \App\Models\Cabang::factory(),
        ];
    }
}
