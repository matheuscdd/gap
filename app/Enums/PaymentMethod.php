<?php

namespace App\Enums;

enum PaymentMethod:string {
    case PIX = 'pix';
    case TICKET = 'ticket';
}
