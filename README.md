# Clean Checkout for Magento 2

A drop-in replacement for the Magento 2 checkout.

## Features

## Modules

The project is divided into several modules:

- Overall checkout improvements, such as; ([Core Module](https://github.com/danslo/CleanCheckoutCore))
    - Show input labels next to the fields
    - Allow disabling checkout fields like telephone, company, etc.
    - Remove the header and footer from the checkout entirely.
    - Force totals mode, so the customer is aware of totals during entire checkout.
    - In addition to the above, always show cart items for additional clarity.
    - Move cart/totals blocks to more logical places.
    - Remove jerkiness when switching between checkout steps, and allow setting of step transaction speed.
    - Allow disabling of useless shipping method step with just 1 available option.
    - Allow changing of checkout colors using useful colorpickers.
    - Uses [Font Awesome](http://fontawesome.io/) to provider better icons.
- [Geo IP](https://github.com/danslo/CleanCheckoutGeo)
: Customer country is detected and immediately injected in the right places so it will be used for Shipping and Billing country fields and for calculating tax estimates.
- [Social Login](https://github.com/danslo/CleanCheckoutSocial): Uses [Hybridauth](https://hybridauth.github.io/) to allow customers to login with their favorite social media.
- [Newsletter](https://github.com/danslo/CleanCheckoutNewsletter): Lets customers immediately subscribe to the newsletter from the final step in the checkout.
- [Address Autocompletion]((https://github.com/danslo/CleanCheckoutAutocomplete)): Using the [Google Maps Autocomplete API](https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform), we can significantly reduce the number of fields required by the customer.
- Field Order: Allow administrators to choose their own order of checkout fields.
- [A simple but effective default theme](https://github.com/danslo/CleanCheckoutTheme)

All of these features are highly customizable from the backend.


## Installation

```bash
$ composer require rubic/magento2-module-clean-checkout
```

Note: getting an error while installing? Look [here](https://github.com/danslo/CleanCheckout/issues/5#issuecomment-427355564) for a temporary workaround.

## Screenshot

![Image of Clean Checkout](https://i.imgur.com/Fs7So1d.png)

## Demo

You can find a demo environment [here](https://demo.cleancheckout.com/). Please note that it may not be running the latest version of this module at all times.

## Frequently Asked Questions

See [this](https://github.com/danslo/CleanCheckout/wiki/Frequently-Asked-Questions) wiki page.

## Reporting Issues

Before you create an issue, ensure that the incorrect behavior you are encountering doesn't also appear without this module.  This ensures it's not a configuration issue or Magento 2 bug.

## License

Copyright 2018 Rubic

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
