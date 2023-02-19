# Send Status

This module send an email to the customer when the order status change. 

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is SendInvoice.
* Activate it in your thelia administration panel

## Usage

This module send an email when the order status change.
For example, it can be use for a partial delivery

For now, you need to create a status in BO which id is 7.
If your status is something other 7, go to SendConfirmationEmail listener and change the line 63 by whatever you want.

You might want to customize the mail.

## Other ?

Maybe I will add the possibility to create a status by the module.