# SVEA Downloads

Ett WordPress-plugin som visar totala antalet downloads för SVEA Checkout for WooCommerce.

## Vad pluginet gör

Pluginet lägger till en widget i WordPress admin dashboard.

Widgeten visar hur många gånger SVEA Checkout for WooCommerce har laddats ner från WordPress.org.

## Installation

1. Lägg pluginet i `wp-content/plugins/`.
2. Aktivera pluginet i WordPress admin.
3. Gå till WordPress Dashboard.
4. Widgeten för SVEA downloads visas där.

## Hur pluginet är uppbyggt

Pluginet är uppdelat i olika delar där API-anrop och admin-funktionen är separerade.

### API

Jag använder WordPress.org Plugin Statistics API för att hämta statistik över antal downloads.

Endpoint:

`https://api.wordpress.org/stats/plugin/1.0/downloads.php`

API-anropet använder pluginets slug:

`svea-checkout-for-woocommerce`

Jag använder parametern `historical_summary=1` för att få tillbaka `today`, `yesterday`, `last_week` och `all_time`.

Det är värdet `all_time` som används av pluginet.

Parametern är inte dokumenterad i den officiella API-dokumentationen.

Jag hittade information om den här:

https://wordpress.stackexchange.com/questions/84254/wp-org-api-accessing-plugin-downloads-today-value

Klassen som hanterar kontakten med WordPress.org API är:

`includes/class-svea-dwnlds-api.php`

Klassen:

1. Gör API-anropet.
2. Kontrollerar errors och HTTP status.
3. Läser JSON-svaret.
4. Kontrollerar att `all_time` finns.
5. Returnerar download count som ett heltal.

### Admin

`admin/class-svea-dwnlds-admin.php`

Den här klassen hanterar dashboard-widgeten.

Den hämtar download count från API-klassen och visar resultatet i WordPress admin.

Om API-anropet ger ett error visas felmeddelandet istället för download count.

### Main plugin class

`includes/class-svea-dwnlds.php`

Den här klassen startar pluginet och kopplar ihop API-klassen med admin-klassen.

## Error handling

Jag har separerat olika typer av fel.

Om WordPress.org inte går att nå returnerar `wp_remote_get()` ett `WP_Error`.

Om API:et svarar men ger en annan HTTP status än `200`, returneras ett eget `WP_Error`.

Jag kontrollerar också att API-svaret innehåller `all_time` innan värdet används.

## Caching

Download-statistiken cachas med WordPress Transient API i 1 timme.

Det gör att pluginet inte behöver göra ett nytt API-anrop varje gång dashboarden laddas. Istället används det cachade värdet så länge transienten är giltig.

När transienten har gått ut hämtas ny information från WordPress.org och det nya värdet sparas i cachen igen.

Cachens längd är satt till 1 timme med `HOUR_IN_SECONDS`.

## Testing

Jag har testat bland annat:

* att ett normalt API-svar fungerar
* att ett ogiltigt API-svar hanteras
* att WordPress.org inte svarar
* att rätt download count visas
* att cache används när den finns
* att ett nytt API-anrop görs när cachen har gått ut

## Development time

- Research + planering: 30 min
- Filer och foldrar: 5 min (baserat på en WordPress-plugin boilerplate)
- `class-svea-dwnlds-admin.php`: 10 min
- `class-svea-dwnlds-api.php`: 50 min
- Testning: 45 min
- Dokumentation: 20 min

**Totalt: 2 timmar och 40 minuter**

## Author

Johan Malmström
