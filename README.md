![Release](https://shields.io/github/v/release/gnugomez/hifi?include_prereleases&sort=date)
![License](https://shields.io/github/license/gnugomez/hifi)
![MostUsedLang](https://shields.io/github/languages/top/gnugomez/hifi)
[![Build Status](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Factions-badge.atrox.dev%2Fgnugomez%2Fhifi%2Fbadge%3Fref%3Dmain&style=flat)](https://actions-badge.atrox.dev/gnugomez/hifi/goto?ref=main)
# HiFi Songs Repo

👨🏻‍🎤 A web app to share High Fidelity music with other people.

You can find the deploy here: [https://gnugomez-hifi.herokuapp.com](https://gnugomez-hifi.herokuapp.com)


## ⚙️ Setup

### Requeriments

- `PHP ^8.0.0`
- `MySQL DB`
- `php composer`

1. Clone repo

```
git clone https://github.com/gnugomez/hifi.git
cd hifi
```

2. Install composer vendors

```
composer install
```

3. Config .env
   `Rename env.sample and fill the variables with the expected data.`

## 👍 Current specs

- `PHP ^8.0.0`
- `MySQL DB`
- This project uses [`AltRouter`](https://github.com/dannyvankooten/AltoRouter) to process all petitions inside index file (with a rewrite rule in .htaccess, only apache).

## ⏱ To do specs

- [x] Implement [`AltRouter`](https://github.com/dannyvankooten/AltoRouter).
- [x] Code Login and register with bcrypt and prepared statements to prevent SQL Injection ad tags stripped to prevent XSS attacks.
- [x] Code sessions with tokens also stored inside server side with php sessions.
- [x] Extend AltoRouter fuctionality to use middlewares to guard routes.
- [x] Implement [`Twig`](https://github.com/twigphp/Twig) to render php values inside html without the need to exec anything there.
- [ ] Implement module system to register separate modules with their own router.
- [ ] Implement content type system to manage data more quick and secure.
- [ ] Create a profile page where the user will be able to change personal data and upload a profile pic.
- [ ] Implement shortcodes to be able to declare components with [`thunderer/Shortcode`](https://github.com/thunderer/Shortcode).

## 🧮 env

```
DB_URL="mysql://user:pass@server:port/db"
```
