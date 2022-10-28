# senangPay for Magento 2.4

Accept payment using senangPay for Magento 2.4.x

**Version: 1.0.0**

#### Installation

1. Create a folder in `<your-magento2-root>/app/code/Senangpay/SenangpayPaymentGateway`.
1. Copy all files to `<your-magento2-root>/app/code/Senangpay/SenangpayPaymentGateway` folder directory.
1. Enable plugin.
    ```bash
    ./bin/magento module:enable Senangpay_SenangpayPaymentGateway --clear-static-content
    ```
1. Run database upgrade.
    ```bash
    ./bin/magento setup:upgrade
    ```
1. Run compilation process.
    ```bash
    ./bin/magento setup:di:compile
    ```
1. Flush cache
    ```bash
    ./bin/magento cache:flush
    ```
1. Configure it in `Stores > Configuration > Sales > Payment Methods > senangPay`.
1. Get senangPay Secret Key and Merchant ID and update in the config.

#### senangPay Dashboard Configuartion

1. Login to **senangPay Dashboard**
2. Navigate to **Settings >> Profile >> Shopping Cart Integration Link**
2. Choose **SHA256** for the **Hash Type Preference**
3. Fill in the **Return URL** with [your-website]/senangpay/checkout/redirect
4. Fill in the **Callback URL** with [your-website]/senangpay/checkout/callback

#### Test Mode

This extension also include a testing mode where you can do a test run on senangPay sandbox environment. To use sandbox mode, tick using sandbox option in the payment setting. Create an account in [senangPay Sandbox](https://sandbox.senangpay.my) environment. 
