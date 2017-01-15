# Magento CoinGate Plugin

CoinGate bitcoin payment gateway Magento plugin.

## Install

Sign up for CoinGate account at <https://coingate.com> for production and <https://sandbox.coingate.com> for testing (sandbox) environment.

Please note, that for "Test" mode you **must** generate separate API credentials on <https://sandbox.coingate.com>. API credentials generated on <https://coingame.com> will **not** work for "Test" mode.

### via [Magento Connect](https://www.magentocommerce.com/magento-connect)

1. Login to Magento Admin Panel.

2. Go to *System » Magento Connect » Magento Connect Manager*.

3. Find *Paste extension key to install*, enter this url: `http://connect20.magentocommerce.com/community/CoinGate_For_Magento`, click **Install**, then click **Proceed**.

4. Click **Return to Admin**.

5. Go to *System » Configuration*.

6. Click on **Payment Methods** in *SALES* block.

7. In *Payment Methods* find and click on **Bitcoin via CoinGate.com**.

8. Set *Enabled* to **Yes**.

9. Set *Currency you want to receive* to the currency in which you wish to receive your payouts from CoinGate. Please note, that if you set it to **Euros** or **US Dollars** you will have to get verified as a merchant (to do that, login to your CoinGate account and click *Verification*. If you set your receive currency to **Bitcoin** you will **not** have to get verified.

10. Enter your API credentials: *App ID*, *API Key*, *API Secret* (to create your API credentials: login to your CoinGate account, go to *API » Apps*, click **+New App**, then click **Submit**).

11. Click **Save Config**.

If the plugin does not work, go to *System » Cache Management* and click on the **Flush Cache Storage** button.
