<?php

Event::listen('order.beforeCreate', 'Octommerce\Shipping\Listeners\CheckIsShippingSelected');

Event::listen('order.afterCreate', 'Octommerce\Shipping\Listeners\AddShippingCost');
Event::listen('order.afterCreate', 'Octommerce\Shipping\Listeners\MarkAsCod');
Event::listen('order.afterCreate', 'Octommerce\Shipping\Listeners\AddShippingDetailsToOrder');
