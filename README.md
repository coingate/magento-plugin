# Magento CoinGate Plugin

Accept Bitcoin & Altcoins on your Magento store.

Read the plugin installation instructions below to get started with CoinGate Bitcoin & Altcoin payment gateway on your shop.
Full setup guide with screenshots is also available on our blog: <https://blog.coingate.com/2017/05/install-magento-bitcoin-altcoins-plugin/>

## Install

Sign up for CoinGate account at <https://coingate.com> for production and <https://sandbox.coingate.com> for testing (sandbox) environment.

Please note, that for "Test" mode you **must** generate separate API credentials on <https://sandbox.coingate.com>. API credentials generated on <https://coingate.com> will **not** work for "Test" mode.

### via FTP

1. Download plugin: https://github.com/coingate/magento-plugin/releases/download/v1.0.9/CoinGate_For_Magento-1.0.9.tgz

2. Extract archive and upload `app` directory to root directory of your Magento store.

3. Login to Admin panel.

4. Go to *System » Configuration*.

5. Click on **Payment Methods** in *SALES* block.

6. In *Payment Methods* find and click on **Bitcoin via CoinGate.com**. Please note, [clear Magento cache](http://docs.magento.com/m1/ce/user_guide/system-operations/cache-clear.html) if payment method not appeared.

7. Set *Enabled* to **Yes**.

8. Set *Currency you want to receive* to the currency in which you wish to receive your payouts from CoinGate. Please note, that if you set it to **Euros** or **US Dollars** you will have to get verified as a merchant (to do that, login to your CoinGate account and click *Verification*. If you set your receive currency to **Bitcoin** you will **not** have to get verified.

9. Enter your API credentials: *App ID*, *API Key*, *API Secret* (to create your API credentials: login to your CoinGate account, go to *API » Apps*, click **+New App**, then click **Submit**).

10. Click **Save Config**.

[Clear Magento cache](http://docs.magento.com/m1/ce/user_guide/system-operations/cache-clear.html) if the plugin does not work, go to *System » Cache Management* and click on the **Flush Cache Storage** button.

#### Please note

You must setup Magento cron jobs to deliver **order confirmation** (and other transactional) **e-mails**:

> Several Magento features require at least one cron job, which schedules activities to occur in the future. A partial list of these activities follows:
>
> ...
>
> Magento EE 1.14.1 and later, Magento CE 1.9.1 and later All Magento e-mails (including order confirmation and transactional)

Cron jobs setup instruction:
http://devdocs.magento.com/guides/m1x/install/installing_install.html#install-cron
