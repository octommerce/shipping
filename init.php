<?php

Event::listen('order.afterCreate', 'Octommerce\Shipping\Listeners\AddShippingCost');
Event::listen('order.afterCreate', 'Octommerce\Shipping\Listeners\MarkAsCod');
