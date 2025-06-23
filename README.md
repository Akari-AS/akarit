# 📄 Google Workspace Akari No - Prosjektdokumentasjon

## 1. Introduksjon

### Formål
Dette dokumentet gir en strukturert dokumentasjon av webapplikasjonskodebasen for Google Workspace Akari No. Målet er å gjøre det enkelt for utviklere å forstå, sette opp og videreutvikle prosjektet.

### Målgruppe
- Webutviklere (frontend og backend)
- DevOps-ingeniører
- Tekniske prosjektledere

### Teknologistack
- **Frontend:** PHP, JavaScript, CSS
- **Backend:** PHP
- **Database:** MySQL
- **Verktøy:** Composer (PHP package manager)

### Forutsetninger
- Grunnleggende kjennskap til Git og versjonskontroll
- Erfaring med PHP og JavaScript
- Kjennskap til terminal/kommandolinje
- Forståelse av MySQL databaser

---

## 2. Komme i gang

### Klone repository
```sh
git clone [REPOSITORY_URL]
cd googleworkspace-akari-no
```

### Installasjon av avhengigheter

```sh
composer install
```

### Miljøvariabler

Opprett en `.env`-fil i rotmappen. Eksempel:
```
DB_HOST=localhost
DB_USER=[USERNAME]
DB_PASSWORD=[PASSWORD]
DB_NAME=[DATABASE_NAME]
SMTP_HOST=[SMTP_SERVER]
SMTP_USER=[SMTP_USERNAME]
SMTP_PASSWORD=[SMTP_PASSWORD]
```
> **NB:** Ikke sjekk inn `.env`-filer i versjonskontroll.

### Kjøre applikasjonen lokalt

Bruk en PHP-server:
```sh
php -S localhost:8000 -t public
```

---

## 3. Arkitektur og mappestruktur

### Overordnet arkitektur

- **Frontend:** PHP templates med JavaScript forbedringer
- **Backend:** PHP-basert logikk for form-håndtering og API-endepunkter
- **Database:** MySQL for datalagring

### Mappestruktur

```
googleworkspace-akari-no/
├── config/                 # Konfigurasjonsfiler
│   └── locations/         # Regionale lokasjonsdata
├── content/               # Innholdsfiler
│   ├── articles/         # Artikler i markdown-format
│   └── seminars/         # Seminar-informasjon
├── public/               # Offentlig tilgjengelige filer
│   ├── assets/          # Statiske ressurser (CSS, JS, bilder)
│   ├── internal/        # Interne sider
│   └── presentasjon/    # Presentasjonsmateriale
├── src/                  # Kildekode
│   └── forms/           # Form-håndteringsklasser
├── templates/            # PHP-maler
│   ├── forms/           # Skjemamaler
│   └── sections/        # Seksjonskomponenter
└── vendor/              # Composer-avhengigheter
```

### Kjernekomponenter

- `public/index.php`: Hovedinngang for applikasjonen
- `src/forms/FormHandler.php`: Håndterer skjemainnsendinger
- `templates/`: Inneholder alle PHP-maler for visning
- `config/`: Konfigurasjonsfiler og regionale data

---

## 4. Konfigurasjon

### Generell konfigurasjon

Konfigurasjon finnes i `config/`-mappen:
- Lokasjonsspesifikke innstillinger i `config/locations/`
- Workspace-verktøydata i `config/workspace_tools_data.php`

### Databasekonfigurasjon

Database-tilkobling konfigureres via miljøvariabler i `.env`-filen.

### Form-håndtering

Skjemahåndtering er implementert i `src/forms/`-mappen med følgende hovedklasser:
- `FormHandler.php`: Basis skjemahåndtering
- `ContactFormHandler.php`: Kontaktskjemahåndtering
- `SeminarFormHandler.php`: Seminarpåmeldingshåndtering

---

## 5. Tilpasning og utvidelse

### Legge til nye sider

1. Opprett en ny PHP-template i `templates/`
2. Legg til ruting i `public/index.php` hvis nødvendig
3. Oppdater navigasjon i `templates/header.php`

### Legge til nye skjemaer

1. Opprett ny skjemaklasse i `src/forms/`
2. Lag tilhørende template i `templates/forms/`
3. Implementer validering og e-postfunksjonalitet

### Styling og temaer

- CSS-filer finnes i `public/assets/css/`
- Hovedstilark: `public/assets/css/style.css`
- Partials i `public/assets/css/partials/`

---

## 6. Feilsøking og ofte stilte spørsmål (FAQ)

### Vanlige problemer

- **Feil: "Database connection failed"**  
  Løsning: Sjekk `.env`-innstillinger og at MySQL-serveren kjører.

- **Feil: "Cannot send email"**  
  Løsning: Verifiser SMTP-innstillinger i `.env`

- **Feil: "Form submission failed"**  
  Løsning: Sjekk loggfiler og form-validering

### Logging

- PHP-feil logges til standard PHP error log
- Skjemainnsendinger logges i databasen
- Frontend-feil vises i nettleserens konsoll

### Bidra

- Fork prosjektet
- Lag en ny branch for endringer
- Send pull request med beskrivelse av endringer

---

## 7. Lisens

Proprietær programvare. Alle rettigheter forbeholdt.

--- 