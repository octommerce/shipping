<?php

Event::listen('order.beforeCreate', 'Octommerce\Shipping\Listeners\CheckIsShippingSelected');

Event::listen('order.afterCreate', 'Octommerce\Shipping\Listeners\MarkAsCod');
Event::listen('order.afterCreate', 'Octommerce\Shipping\Listeners\AddShippingDetailsToOrder');

Event::listen('order.beforeAddInvoice', 'Octommerce\Shipping\Listeners\AddInvoiceItem');