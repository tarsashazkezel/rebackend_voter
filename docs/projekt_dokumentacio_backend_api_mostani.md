# 🏢 Társasházi közgyűlés – Backend API dokumentáció

Ez a dokumentum a **Laravel alapú backend rendszer** fejlesztői és felhasználói (frontend) dokumentációját tartalmazza.

---

# 📘 I. Fejlesztői dokumentáció

## 🧱 Architektúra áttekintés

A projekt **réteges architektúrát** használ:

- **Controller** – HTTP kérések kezelése, jogosultság ellenőrzés
- **Service** – üzleti logika és adatkezelés
- **Policy / Ability** – jogosultságkezelés
- **Resource** – frontendnek küldött JSON struktúrák
- **Trait** – közös logika (admin override, response)
- **Request** – validáció
- **Model** – adatbázis leképezés

---

## 🧩 Modellek

### User
- `isAdmin()` – Megállapítja, hogy a felhasználó admin szerepkörrel rendelkezik.

### Role
- A felhasználó szerepkörét reprezentálja (admin, owner, observer).

### Meeting
- Egy közgyűlést reprezentál, kapcsolódik napirendi pontokhoz és létrehozóhoz.

### AgendaItem
- Egy közgyűlés egy napirendi pontját írja le.

### Resolution
- Egy napirendi ponthoz tartozó határozat.

### Vote
- Egy felhasználó szavazata egy határozatra.

---

## 🎮 Controllerek

### AuthController
- `register()` – Új felhasználó regisztrálása és token generálása.
- `login()` – Felhasználó hitelesítése és token kiadása.
- `logout()` – Aktív token visszavonása.

### MeetingController
- `index()` – Közgyűlések listázása.
- `store()` – Új közgyűlés létrehozása.
- `show()` – Egy közgyűlés részleteinek lekérése.
- `update()` – Közgyűlés módosítása.
- `destroy()` – Közgyűlés törlése.
- `report()` – JSON alapú jegyzőkönyv generálása.
- `pdf()` – PDF jegyzőkönyv generálása.

### AgendaItemController
- `store()` – Napirendi pont létrehozása.
- `update()` – Napirendi pont módosítása.
- `destroy()` – Napirendi pont törlése.

### ResolutionController
- `index()` – Határozatok listázása.
- `store()` – Határozat létrehozása.
- `show()` – Határozat részletei.
- `update()` – Határozat módosítása.
- `destroy()` – Határozat törlése.

### VoteController
- `store()` – Szavazat leadása egy határozatra.

### UserController
- `index()` – Felhasználók listázása (admin).
- `show()` – Felhasználó adatainak lekérése.
- `update()` – Felhasználó szerepkörének vagy tulajdoni hányadának módosítása.
- `destroy()` – Felhasználó törlése.

---

## 🧠 Service osztályok

### MeetingService
- CRUD műveletek közgyűlésekhez, kapcsolatok betöltésével.

### AgendaItemService
- Napirendi pontok létrehozása, módosítása, törlése.

### ResolutionService
- Határozatok kezelése és részleteinek betöltése.

### VoteService
- Szavazat rögzítése és szavazási eredmény számítása.

### MeetingReportService
- Jegyzőkönyv adatstruktúra előállítása JSON vagy PDF számára.

### UserService
- Felhasználók listázása, frissítése és törlése.

### AbilityService
- Szerepkörhöz tartozó ability-k meghatározása.

---

## 🛡️ Policy-k

### MeetingPolicy
- Meghatározza, hogy ki hozhat létre, módosíthat vagy törölhet közgyűlést.

### AgendaItemPolicy
- Napirendi pontokra vonatkozó jogosultságellenőrzés.

### ResolutionPolicy
- Határozatok kezelésének jogosultságai.

### VotePolicy
- Szavazás engedélyezése, egyszeri szavazás biztosítása.

### UserPolicy
- Felhasználók kezelésének admin jogosultság ellenőrzése.

---

## 🧬 Traitek

### HandlesAdminOverride
- Policy-kben biztosítja az admin felhasználók automatikus engedélyezését.

### ApiResponse
- Egységes JSON API válaszok biztosítása a controllerekben.

---

## 🧾 Request osztályok

### LoginRequest
- Bejelentkezési adatok validálása.

### RegisterRequest
- Regisztrációs adatok validálása.

---

## 📦 Resource osztályok

### UserResource
- Frontend számára biztonságos felhasználói adatstruktúra.

### MeetingResource
- Közgyűlés adatainak strukturált átadása.

### AgendaItemResource
- Napirendi pont és határozatok formázása.

### ResolutionResource
- Határozat és szavazatok megjelenítése.

### VoteResource
- Szavazat és kapcsolódó felhasználó megjelenítése.

---

# 📗 II. Felhasználói (Frontend) dokumentáció

## 🔐 Hitelesítés

### Regisztráció
- `POST /api/register`

### Bejelentkezés
- `POST /api/login`

A válaszban kapott token minden további kéréshez szükséges:

```
Authorization: Bearer {token}
```

---

## 📡 API végpontok

### Közgyűlések
- `GET /api/meetings`
- `POST /api/meetings`
- `GET /api/meetings/{id}`
- `PUT /api/meetings/{id}`
- `DELETE /api/meetings/{id}`

### Napirendi pontok
- `POST /api/agenda-items`
- `PUT /api/agenda-items/{id}`
- `DELETE /api/agenda-items/{id}`

### Határozatok
- `GET /api/resolutions`
- `POST /api/resolutions`
- `GET /api/resolutions/{id}`
- `PUT /api/resolutions/{id}`
- `DELETE /api/resolutions/{id}`

### Szavazás
- `POST /api/resolutions/{id}/vote`

### Felhasználók (admin)
- `GET /api/users`
- `GET /api/users/{id}`
- `PUT /api/users/{id}`
- `DELETE /api/users/{id}`

---

## ⚙️ Backend beüzemelése

1. Projekt klónozása
2. `.env` fájl létrehozása `.env.example` alapján
3. Függőségek telepítése:
   ```bash
   composer install
   ```
4. App key generálása:
   ```bash
   php artisan key:generate
   ```
5. Adatbázis migrálás és seed:
   ```bash
   php artisan migrate --seed
   ```
6. Sanctum publikálása:
   ```bash
   php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
   ```
7. Szerver indítása:
   ```bash
   php artisan serve
   ```

A backend ezután elérhető lesz az Angular frontend számára.

---

## ✅ Összegzés

Ez a backend rendszer:
- szerepkör-alapú jogosultságkezelést használ
- Service + Policy architektúrára épül
- frontend-barát Resource réteget biztosít
- token alapú (Sanctum) authentikációt alkalmaz

🚀 Teljes mértékben alkalmas Angular alapú frontend kiszolgálására.

