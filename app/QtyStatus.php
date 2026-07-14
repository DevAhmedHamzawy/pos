<?php

namespace App;

enum QtyStatus: string
{
    case StockPurchase = 'stock_purchase';
    case Return = 'return';
    case Damaged = 'damaged';
    case InventoryCorrection = 'inventory_correction';
    case Other = 'other';
}
