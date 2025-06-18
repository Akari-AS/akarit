# Form Handler - Modulær Struktur

Denne mappen inneholder den refaktorerte, modulære strukturen for skjemahåndtering på nettstedet.

## Struktur

### Hovedfiler

- **`FormHandler.php`** - Hovedkoordinator som ruter skjemaer til riktig handler
- **`src/form_handler.php`** - Legacy entry point som nå bare inkluderer den nye strukturen

### Moduler

#### `CalendarHelper.php`
- **Ansvar**: Kalenderrelaterte funksjoner
- **Funksjoner**:
  - `generateIcsContent()` - Genererer ICS-fil innhold for kalenderinvitasjoner
  - `generateGoogleCalendarLink()` - Lager Google Calendar lenker
  - `escapeIcsText()` - Escaper tekst for ICS-format

#### `FormValidator.php`
- **Ansvar**: Validering av skjemadata
- **Funksjoner**:
  - `validateCommonFields()` - Validerer felles felter (navn, e-post, telefon, personvern)
  - `validateContactForm()` - Validerer kontaktskjema-spesifikke felter
  - `validateSeminarForm()` - Validerer seminar påmelding-spesifikke felter
  - `validateServerConfig()` - Sjekker serverkonfigurasjon

#### `DatabaseService.php`
- **Ansvar**: Database operasjoner
- **Funksjoner**:
  - `saveContactForm()` - Lagrer kontaktskjema til database
  - `saveSeminarRegistration()` - Lagrer seminar påmelding til database
  - `getConnection()` - Henter database tilkobling (singleton)

#### `MailService.php`
- **Ansvar**: E-post funksjonalitet
- **Funksjoner**:
  - `sendContactFormToAdmin()` - Sender kontaktskjema til admin
  - `sendContactFormReceipt()` - Sender kvittering til bruker for kontaktskjema
  - `sendSeminarRegistrationToAdmin()` - Sender seminar påmelding til admin
  - `sendSeminarRegistrationReceipt()` - Sender kvittering med ICS til bruker

#### `ContactFormHandler.php`
- **Ansvar**: Prosessering av kontaktskjema
- **Funksjoner**:
  - `process()` - Hovedmetode for kontaktskjema prosessering

#### `SeminarFormHandler.php`
- **Ansvar**: Prosessering av seminar påmelding
- **Funksjoner**:
  - `process()` - Hovedmetode for seminar påmelding prosessering

## Fordeler med den nye strukturen

1. **Separation of Concerns**: Hver klasse har ett spesifikt ansvar
2. **Testbarhet**: Enklere å teste individuelle komponenter
3. **Vedlikehold**: Lettere å finne og endre spesifikk funksjonalitet
4. **Gjenbrukbarhet**: Komponenter kan gjenbrukes i andre deler av systemet
5. **Lesbarhet**: Koden er mer oversiktlig og lettere å forstå
6. **Skalerbarhet**: Enkelt å legge til nye skjematyper

## Bruk

Den gamle `src/form_handler.php` fungerer fortsatt som før - den inkluderer bare den nye strukturen. Ingen endringer er nødvendige i andre deler av systemet.

## Legge til nye skjematyper

1. Opprett en ny handler-klasse (f.eks. `NewsletterFormHandler.php`)
2. Legg til validering i `FormValidator.php` hvis nødvendig
3. Legg til database operasjoner i `DatabaseService.php` hvis nødvendig
4. Legg til e-post funksjonalitet i `MailService.php` hvis nødvendig
5. Oppdater `FormHandler.php` med ny case i switch-setningen

## Miljøvariabler

Systemet bruker følgende miljøvariabler:
- `MAILGUN_SMTP_HOST`, `MAILGUN_SMTP_PORT`, `MAILGUN_SMTP_USERNAME`, `MAILGUN_SMTP_PASSWORD`
- `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, `MAIL_RECIPIENT_ADDRESS`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` 