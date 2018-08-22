# Magento Vue Storefront utils

This Magento extension enhances the [mage2vuestorefront](https://github.com/DivanteLtd/mage2vuestorefront) idnexer by providing on-demand Product indexing based on web-hooks mechanism.

This module is designed to work with: [mage2vuestorefront](https://github.com/DivanteLtd/mage2vuestorefront) and [Vue Storefront](https://github.com/DivanteLtd/vue-storefront).

## Installation guide

0. Integrate Your Magento2 instance with Vue Storefront: [tutorial](https://medium.com/@piotrkarwatka/vue-storefront-cart-totals-orders-integration-with-magento2-6fbe6860fcd), [video tutorial](https://www.youtube.com/watch?v=CtDXddsyxvM)
1. Setup [mage2vuestorefront](https://medium.com/@piotrkarwatka/vue-storefront-how-to-install-and-integrate-with-magento2-227767dd65b2) bridge for product imports.
2. Please copy the `"Divante"` folder containing the extension to Your Magento modules directory (`app`)
3. Run `php bin/magento setup:upgrade`
4. Configure VsBridge module and mage2vuestorefront to work together by executing the steps desribed below.

### On-demand indexer (experimental!) support for mage2vuestorefront

Mage2vuestorefront supports an on-demand indexer - where Magento calls a special webhook to update modified products.
In the current version the webhook notifies mage2vuestorefront about changed product SKUs and mage2vuestorefront pulls the modified products data via Magento2 APIs.

To start mage2vuestorefront in the on-demand mode, please execute the following steps.

```bash
cd mage2vuestorefront/src
export TIME_TO_EXIT=2000
export MAGENTO_CONSUMER_KEY=byv3730rhoulpopcq64don8ukb8lf2gq
export MAGENTO_CONSUMER_SECRET=u9q4fcobv7vfx9td80oupa6uhexc27rb
export MAGENTO_ACCESS_TOKEN=040xx3qy7s0j28o3q0exrfop579cy20m
export MAGENTO_ACCESS_TOKEN_SECRET=7qunl3p505rubmr7u1ijt7odyialnih9
export PORT=6060
export MAGENTO_URL=http://demo-magento2.vuestorefront.io/rest

node --harmony webapi.js
```

The API will be listening on port 6060. Typically non-standard ports like this one are not exposed on the firewall. Please consider setting up [simple nginx proxy for this service](https://www.digitalocean.com/community/tutorials/how-to-set-up-a-node-js-application-for-production-on-ubuntu-16-04).

Anyway - this API must be publicly available via Internet OR You must have the mage2vuestorefront installed on the same machine like Magento2.

Go to Your Magento2 admin panel, then to Stores -> Configuration -> VsBridge and set-up "Edit product" url to: `http://localhost:6060/magento/products/update`. **Please note:** Product delete endpoint hasn't been implemented yet and it's good chance for Your PR.

After having the webapi up and runing and this endpoint set, any Product save action will call `POST http://localhost:6060/magento/products/update` with the body set to `{"sku": ["modified-sku-list"]}`.

Webapi will [add the products to the queue](https://github.com/DivanteLtd/mage2vuestorefront/blob/b44e7ede9aeb27f308e2a87033251a2491640da8/src/api/routes/magento.js#L19).

Please run the queue worker to process all the queued updates (You may run multiple queue workers even distributed across many machines):

```bash
cd mage2vuestorefront/src
export TIME_TO_EXIT=2000
export MAGENTO_CONSUMER_KEY=byv3730rhoulpopcq64don8ukb8lf2gq
export MAGENTO_CONSUMER_SECRET=u9q4fcobv7vfx9td80oupa6uhexc27rb
export MAGENTO_ACCESS_TOKEN=040xx3qy7s0j28o3q0exrfop579cy20m
export MAGENTO_ACCESS_TOKEN_SECRET=7qunl3p505rubmr7u1ijt7odyialnih9
export PORT=6060
export MAGENTO_URL=http://demo-magento2.vuestorefront.io/rest

node --harmony cli.js productsworker
```

**Please note:** We're using [kue based on Redis queue](https://github.com/Automattic/kue) which may be configured via `src/config.js` - `kue` + `redis` section.
**Please note:** There is no authorization mechanism in place for the webapi calls. Please keep it local / private networked or add some kind of authorization as a PR to this project please :-)


## Credits

Mateusz Bukowski (@gatzzu)
