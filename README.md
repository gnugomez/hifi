# HiFi Songs Repo
[![Heroku](https://heroku-badge.herokuapp.com/?app=gnugomez-hifi)](https://gnugomez-hifi.herokuapp.com)
[![Latest Stable Version](https://poser.pugx.org/gnugomez/hifi/v/stable.svg)](https://packagist.org/gnugomez/hifi/shortcode)
[![License](https://poser.pugx.org/gnugomez/hifi/license.svg)](https://packagist.org/packages/gnugomez/hifi)

👨🏻‍🎤 High Fidelity songs repo app

## 👍 Current specs
- `PHP ^8.0.0`
- `MySQL DB`
- This project uses [`AltRouter`](https://github.com/dannyvankooten/AltoRouter) to process all petitions inside index file (with a rewrite rule in .htaccess, only apache).
## ⏱ To do specs
- [x] Implement [`AltRouter`](https://github.com/dannyvankooten/AltoRouter).
- [ ] Code Login and register with bcript and prepared statements to prevent SQL Injection ad tags stripped to prevent XSS attacks.
- [ ] Code sessions with tokens also stored inside the db.
- [ ] Extend AltoRouter fuctionality to use middlewares to guard routes.
- [ ] Create a profile page where the user will be able to change personal data and upload a profile pic.
- [ ] Implement content type system to manage data more quick and secure.
- [ ] Implement shortcodes to be able to declare components with [`thunderer/Shortcode`](https://github.com/thunderer/Shortcode).
- [ ] Implement [`Twig`](https://github.com/twigphp/Twig) to be ablre to render php values inside html without the need to exec anything there.

## 🧮 env
```
DB_URL="mysql://user:pass@server:port/db"
```

