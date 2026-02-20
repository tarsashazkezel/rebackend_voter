# Tartalomjegyzék
- Tartalomjegyzék
- Előkészületek
- Verzió 0.03
- Verzió 0.02
- Verzió 0.001
  - ha nem lenne letöltve a project
  - Kontrollerek létrehozása
  - Migrációk hozzáadása
  - Modellek hozzáadása
  - Adatbázis létrehozása
  - Controllerek, vezérlők
  - Végpontok
  - Megoldandó problémák
  - A jelenlegi állás 2025.12.01-jével

# Előkészületek:
- angular projekt létrehozása: composer create-project laravel/laravel projektnév
- git inicializálása, majd első commitolás után push
- adatbázis terv
- ha nem lett volna meg, akkor kell egy 
php artisan install:api parancsot futtatni

# Verzió 0.03
## Gondolatok:
- ChatGPT által generált kódok nagyrésze beírva
- harmadik változatú adatbázis a docs mappában
- migrálás megtörtént, mintaadatok a seederekben
- modellek legenerálva, adatbázis kapcsolatok beállítva
- HasRole Trait beállítva
- VoteService elkezdve
- Policy-k elkezdve
## További teendők
- Controllerek legenerálása
- végpontok létrehozása (azaz módosítása a sanctumot is beleemelve)
- LEGFONTOSABB: tesztelés !!!!!
- teljes dokumentáció befejezése

# Verzió 0.02
## Gondolatok
- felhasználói jogkörök: admin, közjegyző, tulajdonos&meghatalmazott


# Verzió 0.001
## ha nem lenne letöltve a project
- `git clone <project_url>` vagy `git pull`
- `composer install`
- .env fájl beszerzése
- `php artisan install:api`


## Kontrollerek létrehozása
- Így lehet Controllert (magyar fordítással vezérlőt) létrehozni:
```bash
php artisan make:controller api/tablanevController
```
- Táblanevek:
  - Tarsashaz, 
  - Alberlet, 
  - Kozgyules, 
  - Tulajdonos, 
  - Nepirendi_pont, 
  - Szavazat, 
  - Resztvevo, 
  - Felszolalas


## Migrációk hozzáadása
```bash
php artisan make:migration create_tablanev_table
```
- a táblák ugyanazok, mint fent
- a táblák mezői a /docs táblában található "adatbazis_terv_elso.png" fájl alapján lesznek hozzáadva
 ![Adatbázis Terv](adatbazis_terv_elso.png)
- pl. 
```bash
    public function up(): void
    {
        Schema::create('tarsashaz', function (Blueprint $table) {
            $table->id();
            $table->string('nev',50);
            $table->string('alapito_dokumentum');
            $table->string('szavazasi_szabaly');
            // $table->timestamps();
        });
    }
```

## Modellek hozzáadása
- először is csinálunk egy modelt, pl:

```bash
php artisan make:model Tarsashaz
```
- utána a modelben meghatározzuk az egy-a-többhöz kapcsolatok fajtáit a táblák viszonylatában
- pl: 
```bash
class Tulajdonos extends Model
{
    public function alberlet(){
        return $this->belongsTo(Alberlet::class);
    }
    public function resztvevo(){
        return $this->hasMany(Resztvevo::class);
    }
}
```
- Ezzel határozható meg a többes oldal:
```bash 
belongsTo() 
``` 
- Ezzel határozható meg az egyes oldal:

 ```bash 
hasMany() 
``` 

- 8 modell lett hozzáaddva a táblanevek alapján
- be lettek állítva az egy-a-többhöz való kapcsolatok

## Adatbázis létrehozása
- a migrációs fájlokhoz hozzá lett addva egy mező nulla-képességét felülíró migráció is
- egyébként így lehet migrációs fájlt létrehozni:
```bash
php artisan make:migration create_example_table
```
- az adatbázis adatait a mesterséges intelligenciával hoztam létre, példaműködést segíteni szimulálni
- a külön táblákhoz külön .sql végkiterjesztésű fájl tartozik a /database könyvtárban
- ezután a következő parancsot kell kiadni a parancssorban, hogy a seederek létrejöjjenek:
```bash
php artisan make:seeder Tulajdonos
```
- ezek száma megegyező a táblák számával
- a seedereken belül a ```public function run(): void {}``` függvényen belül a következő parancsokat kell megadni, ahhoz, hogy az sql utasításokat kezeljék a seederek:
```bash
        $sql = file_get_contents( __DIR__ . "/<tablanev>.sql" );

        DB::unprepared( $sql );
```
- Ezek után a DatabaseSeeder.php fájlon belül meg kell adni a saját seederek lefutási képességét, és sorrendjét az idegen kulcsok hollétének függvényétől:
```bash
$this->call([
            Tarsashaz::class,
            Alberlet::class,
            Kozgyules::class,
            Napirendi_pont::class,
            Tulajdonos::class,
            Resztvevo::class,
            Felszolalas::class,
            Szavazat::class,
        ]);
```
- A legvégén pedig a parancssorból a következő parancsokat kell kiadni, hogy az adatbázis létrejöjjön:
```bash
php artisan migrate
php artisan db:seed
```

## Controllerek, vezérlők
- a fenti pontban leírt módon létrehozott 8 kontroller felépítésében hasonlóan áll fel
```bash
<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\<Modelnev>;

class ExampleController extends Controller
{
    public function getTablanev(){
        $tablanev = Tablanev::all();
        return response()->json($tablanev);
    }
    public function addTablanev(Request $request){
        $tablanev = new Tablanev();
        $tablanev->mezo1 = $request->mezo1;
        $tablanev->mezo2 = $request->mezo2;
        $tablanev->mezo3 = $request->mezo3;
        $tablanev->save();
        return response()->json($tablanev);
    }
    public function updateTablanev(Request $request, $id){
        $tablanev = Tablanev::find($id);
        $tablanev->mezo1 = $request->mezo1;
        $tablanev->mezo2 = $request->mezo2;
        $tablanev->mezo3 = $request->mezo3;
        $tablanev->update();
        return response()->json($tablanev);
    }
    public function deleteTablanev($id){
        $tablanev = Tablanev::find($id);
        $tablanev->delete();
        return response()->json($tablanev);
    }
}
```
- `<Modelnev>`: a létrehozott modell neve
- `ExampleController`: a létrehozott kontroller neve
- `Tablanev`: a létrehozott tábla, és vagy model neve

## Végpontok
- A nyolc controller négy-négy metódusára került végpont, azonban azonos táblák elérésére a http-protokoll változott csak

| Végpont | Http_protokol | metódus neve |
| :---: | :---: | :---: |
| /tulajdonos | get | getTulajdonos |
| /tulajdonos | post | addTulajdonos |
| /tulajdonos | put | updateTulajdonos |
| /tulajdonos | delete | deleteTulajdonos |
| /alberlet | get | getAlberlet |
| /alberlet | post | addAlberlet |
| /alberlet/{id} | put | updateAlberlet |
| /alberlet/{id} | delete | deleteAlberlet |
| /kozgyules | get | getKozgyules |
| /kozgyules | post | addKozgyules |
| /kozgyules/{id} | put | updateKozgyules |
| /kozgyules/{id} | delete | deleteKozgyules |
| /felszolalas | get | getFelszolalas |
| /felszolalas | post | addFelszolalas |
| /felszolalas/{id} | put | updateFelszolalas |
| /felszolalas/{id} | delete | deleteFelszolalas |
| /tarsashaz | get | getTarsashaz |
| /tarsashaz | post | addTarsashaz |
| /tarsashaz/{id} | put | updateTarsashaz |
| /tarsashaz/{id} | delete | deleteTarsashaz |
| /szavazat | get | getSzavazat |
| /szavazat | post | addSzavazat |
| /szavazat/{id} | put | updateSzavazat |
| /szavazat/{id} | delete | deleteSzavazat |
| /resztvevo | get | getResztvevo |
| /resztvevo | post | addResztvevo |
| /resztvevo/{id} | put | updateResztvevo |
| /resztvevo/{id} | delete | destroyResztvevo |
| /napirendipont | get | getNapirendi_pont |
| /napirendipont | post | addNapirendi_pont |
| /napirendipont/{id} | put | updateNapirendi_pont |
| /napirendipont/{id} | delete | deleteNapirendi_pont |

## Megoldandó problémák
- végpontok tesztelése, mert valamiért adott táblaneveket nem talál
- requestek létrehozása
- saját response kontroller kialakítása majd a jobb válaszok reményében
- felhasználó kezelés, beléptetés, regisztráció, kiléptetés
- jelszótárolás titkosítva 

## A jelenlegi állás 2025.12.01-ével

- végpontok tesztelve, és a hiba okának megoldása:
```bash 
Modelleken belül a következő két parancsot kell kiadni:

protected $table = "given_table_name";
public $timestamps = false;
```
- az eddigi hibák okai a következők voltak
  - a: a laravel alapértelmezetten az angol többes szám jelét (s) hozzáadja a beépített sql parancsához, példával élve a `User` modelt a `users` táblából keresi ki
  - b: a `$timpestamps` változó hamis értékre való állításával megakadályozásba kerül a nem létező updated_at mező erőltetett felülírása