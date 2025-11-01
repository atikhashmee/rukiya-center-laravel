<?php

namespace App;

enum PaymentOptions: string
{
    case PAYPAL = 'paypal';
    case STRIPE = 'stripe';
    case LATER = 'later';
}
