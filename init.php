<?php

Event::listen('order.afterCreate', 'Octommerce\Shipping\Listeners\AddShippingCost');
