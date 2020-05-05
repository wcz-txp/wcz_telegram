# wcz_telegram
A really simple and raw plugin for automatic posting new articles to [**Telegram**](https://telegram.org/). All the parameters are yet inside the code. In article window you can choose to send this article to Telegram. Actually it is posted a bold title with the excerpt, followed by the keywords and the link. The link can be modified to use your own template for rendering this article inside Telegram.

* **wcz_telegram_token**: the token of your Telegram bot. Talk to the [**BotFather**](https://core.telegram.org/bots#6-botfather).
* **wcz_telegram_chatid**: the chatid of your Telegram channel. You'll find some hints [**here**](https://stackoverflow.com/questions/45414021/get-telegram-channel-group-id).
* **wcz_telegram_iv** and **wcz_telegram_rhash**: If you have already a [**Telegram Instant View template**](https://instantview.telegram.org/#publishing-templates), but this is not really public, you should consider to use this option. Set it to `'1'` and copy the rhash, so Telegram users will see your article on their mobiles inside Telegram rendered with your template.
* **wcz_telegram_utm**: add parameters to your link, so your web analytics software - eg. Google Analytic, Matomo/Piwik - has some stuff to identify Telegram as the source of your traffic.
* **wcz_telegram_debug**: If something doesn't work, switch it to `'1'` and you'll get the complete request inside of `textpattern/tmp/telegram_request.txt`
